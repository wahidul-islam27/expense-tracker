<!doctype html>
<!-- <html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            margin-top: 50px;
            color: #333;
        }

        table {
            margin-top: 30px;
            border-collapse: collapse;
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f0f0f0;
            color: #555;
        }

        td {
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body> -->

<form method="GET" action="/expense-tracker/public">
    <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px;">
        <div>
            <label for="month">Month:</label>
            <input type="month" name="month" id="month" value="<?= htmlspecialchars($_GET['month'] ?? '') ?>">
        </div>

        <div>
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>" <?= (isset($_GET['category']) && $_GET['category'] == $category->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category->getCategoryName()) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <button type="submit" style="display: inline-block; padding: 6px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">Filter</button>
        </div>
        <div>
            <a href="/expense-tracker/public" style="display: inline-block; padding: 6px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">Clear</a>
        </div>
    </div>
</form>

<h1>Your Expenses</h1>

<table>
    <style>
        h1 {
            margin-top: 50px;
            color: #333;
        }

        table {
            margin-top: 30px;
            margin: auto;
            border-collapse: collapse;
            width: 93%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f0f0f0;
            color: #555;
        }

        td {
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
    <thead>
        <tr>
            <th>Expense ID</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Expense Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($expenses)): ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #999; padding: 20px;">
                    No expenses found.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?= $expense->getId() ?></td>
                    <td><?= htmlspecialchars($expense->getCategory()->getCategoryName()) ?></td>
                    <td><?= $expense->getDescription() ?></td>
                    <td>$<?= number_format($expense->getAmount(), 2) ?></td>
                    <td><?= $expense->getExpenseDate()->format('Y-m-d') ?></td>
                    <td>
                        <a href="edit-expense?id=<?= $expense->getId() ?>" style="margin-right: 8px; text-decoration: none; color: #007BFF;">Edit</a>
                        <form action="delete?id=<?= $expense->getId() ?>" method="POST" style="display:inline;">
                            <button type="submit" style="background: none; border: none; color: red; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- </body>

</html> -->