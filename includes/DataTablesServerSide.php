<?php
/**
 * Server-side processing engine for DataTables (https://datatables.net) ajax
 * requests.
 *
 * WHY THIS EXISTS
 * ----------------
 * The old pattern used across this app was: PHP fetches *every* row from
 * the table, builds the full <tbody> HTML, and simple-datatables.js
 * re-parses that HTML in the browser to paginate/sort/search it. That
 * means every page load pays the cost of the *entire* dataset (query +
 * PHP loop + HTML + browser parsing) even though the user only ever looks
 * at 10-25 rows at a time. It gets linearly slower as data grows.
 *
 * This helper flips that: the browser asks for one page at a time (via
 * ajax), and MySQL does the filtering/sorting/pagination with an indexed
 * LIMIT/OFFSET query. Only the rows actually shown are ever fetched,
 * looped over in PHP, or sent to the browser.
 *
 * HOW TO REUSE FOR ANOTHER PAGE (e.g. pembayaran, jurnal_umum, users)
 * ---------------------------------------------------------------------
 * 1. Create an endpoint under /api (see api/datatable_santri.php).
 * 2. List your columns in the SAME order as your <thead> columns, each
 *    with the raw SQL expression to search/sort on. Use `null` for a
 *    column that has no matching SQL expression (e.g. an "Aksi" button
 *    column) so it's simply not searchable/orderable.
 * 3. Call runServerSideQuery() with your SELECT/FROM (and any fixed
 *    WHERE, e.g. a status filter).
 * 4. Turn each returned row into the final display array (badges,
 *    formatted dates, action buttons, etc) — same as the old foreach
 *    loop, just now scoped to one page of rows instead of all of them.
 * 5. echo json_encode([...]) in the exact DataTables response shape
 *    (see buildDataTablesResponse()).
 */

/**
 * Read a DataTables ajax GET parameter.
 */
function dtParam(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

/**
 * Run one server-side DataTables query: total count, filtered count, and
 * the one page of rows that matches the current search/order/pagination.
 *
 * $config:
 *   conn      (required) mysqli connection
 *   select    (required) raw SQL column list (no "SELECT " prefix)
 *   from      (required) raw SQL "table JOIN table ..." (no "FROM " prefix)
 *   where     (optional) raw SQL condition (no "WHERE " prefix), always applied
 *   group_by  (optional) raw SQL (no "GROUP BY " prefix)
 *   columns   (required) list, ONE ENTRY PER <thead> COLUMN, IN ORDER:
 *               ['db' => 's.nama', 'search' => true, 'order' => true]
 *             pass 'db' => null for columns with no backing SQL column
 *             (action buttons, computed badges, etc).
 *
 * Returns ['draw', 'recordsTotal', 'recordsFiltered', 'rows'].
 */
function runServerSideQuery(array $config): array
{
    $conn = $config['conn'];
    $select = $config['select'];
    $from = $config['from'];
    $baseWhere = $config['where'] ?? '1=1';
    $groupBy = $config['group_by'] ?? '';
    $columns = $config['columns'];

    $draw = (int) dtParam('draw', 1);
    $start = max(0, (int) dtParam('start', 0));
    $length = (int) dtParam('length', 10);
    // Hard ceiling so ?length=999999 can't force a full table scan/dump.
    $length = $length > 0 ? min($length, 200) : 10;

    $groupSuffix = $groupBy !== '' ? " GROUP BY $groupBy" : '';

    // --- total (unfiltered, but honoring any fixed base WHERE) ---
    $totalSql = "SELECT COUNT(*) AS c FROM (SELECT 1 FROM $from WHERE $baseWhere$groupSuffix) t";
    $totalRes = mysqli_query($conn, $totalSql);
    $recordsTotal = $totalRes ? (int) (mysqli_fetch_assoc($totalRes)['c'] ?? 0) : 0;

    // --- search WHERE (global search box, OR'd across searchable columns) ---
    $searchWhere = $baseWhere;
    $searchValue = trim((string) (dtParam('search')['value'] ?? ''));
    if ($searchValue !== '') {
        $escaped = mysqli_real_escape_string($conn, $searchValue);
        $searchable = [];
        foreach ($columns as $col) {
            if (!empty($col['search']) && !empty($col['db'])) {
                $searchable[] = "{$col['db']} LIKE '%$escaped%'";
            }
        }
        if ($searchable) {
            $searchWhere .= ' AND (' . implode(' OR ', $searchable) . ')';
        }
    }

    $filteredSql = "SELECT COUNT(*) AS c FROM (SELECT 1 FROM $from WHERE $searchWhere$groupSuffix) t";
    $filteredRes = mysqli_query($conn, $filteredSql);
    $recordsFiltered = $filteredRes ? (int) (mysqli_fetch_assoc($filteredRes)['c'] ?? 0) : 0;

    // --- order ---
    $orderSql = '';
    $order = dtParam('order');
    if (is_array($order) && isset($order[0]['column'])) {
        $colIndex = (int) $order[0]['column'];
        $dir = (isset($order[0]['dir']) && strtolower((string) $order[0]['dir']) === 'asc') ? 'ASC' : 'DESC';
        if (isset($columns[$colIndex]) && !empty($columns[$colIndex]['order']) && !empty($columns[$colIndex]['db'])) {
            $orderSql = "ORDER BY {$columns[$colIndex]['db']} $dir";
        }
    }
    if ($orderSql === '') {
        foreach ($columns as $col) {
            if (!empty($col['order']) && !empty($col['db'])) {
                $orderSql = "ORDER BY {$col['db']} ASC";
                break;
            }
        }
    }

    $sql = "SELECT $select FROM $from WHERE $searchWhere$groupSuffix $orderSql LIMIT $start, $length";
    $result = mysqli_query($conn, $sql);

    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }

    return [
        'draw' => $draw,
        'start' => $start,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'rows' => $rows,
    ];
}

/**
 * Wrap the final `data` rows into the exact shape DataTables expects.
 */
function buildDataTablesResponse(array $queryResult, array $data): array
{
    return [
        'draw' => $queryResult['draw'],
        'recordsTotal' => $queryResult['recordsTotal'],
        'recordsFiltered' => $queryResult['recordsFiltered'],
        'data' => $data,
    ];
}
