<!doctype html>
<style>
    .header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .btn-dlt {
        margin-top: 20px;
        height: 22px;
        margin-right: 92px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
        font-size: 16px;
    }

    .btn-dlt:hover {
        background-color: #0056b3;
    }
</style>


<div class="header">
    <h2>Expense Report</h2>
    <form method="GET" action="/expense-tracker/public/download">
        <a class="btn-dlt" href="/expense-tracker/public/download?month=<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m') ?>&date=<?= isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') ?>&year=<?= isset($_GET['year']) ? $_GET['year'] : date('Y') ?>" class="btn-download">Download Report</a>
    </form>
</div>

<!-- Filter Form -->
<form method="GET" action="/expense-tracker/public/report" style="margin-bottom: 20px;">
    <label for="month">Month:</label>
    <input type="month" name="month" id="month" value="<?= htmlspecialchars($_GET['month'] ?? '') ?>">

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">

    <label for="year">Year:</label>
    <input type="number" name="year" id="year" min="2000" max="2099" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>">

    <button type="submit">Filter</button>
</form>

<!-- Expenses by Category -->
<?php if (!empty($reports)): ?>
    <div style="width: 80%; margin: auto;">
        <?php foreach ($reports as $report): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <div onclick="toggleDescriptions('desc-<?= md5($report['category']) ?>')">
                    <strong><?= htmlspecialchars($report['category']) ?></strong>

                    <?php if (isset($expenses[$report['category']])): ?>
                        <?php foreach ($expenses[$report['category']] as $expenseList): ?>
                            <?php foreach ($expenseList as $expense): ?>
                                <ul>
                                    <?= htmlspecialchars($expense->getDescription()) ?> - <?= number_format($expense->getAmount(), 2) ?>
                                </ul>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div>No expenses found for this category.</div>
                    <?php endif; ?>
                </div>

                <div id="desc-<?= md5($report['category']) ?>" style="display: none; padding-left: 20px; margin-top: 10px;"></div>
                <div>Total = <?= number_format($report['total'], 2) ?></div>

            </div>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <p style="text-align: center;">No expenses found for the selected filters.</p>
<?php endif ?>

<script>
    function toggleDescriptions(id) {
        var section = document.getElementById(id);
        section.style.display = (section.style.display === 'none') ? 'block' : 'none';
    }
</script>