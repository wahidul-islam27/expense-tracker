<!doctype html>

<style>
    form {
        max-width: 500px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    .form-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    label {
        flex: 1;
        font-weight: bold;
        min-width: 120px;
    }

    input,
    select {
        flex: 2;
        padding: 8px;
        font-size: 1rem;
        width: 100%;
        margin-top: 12px;
    }

    input {
        width: 96%;
    }

    button {
        padding: 10px;
        font-size: 1rem;
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: #218838;
    }
</style>

<h1><?= isset($expense) ? 'Edit Expense' : 'Add New Expense' ?></h1>

<form method="POST" action="<?= isset($expense) ? '/expense-tracker/public/edit-expense' : '/expense-tracker/public/add-expense' ?>">
    <?php if (isset($expense)): ?>
        <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>
    <div>
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category->getId() ?>"
                    <?= isset($expense) && $expense->getCategory()->getId() == $category->getId() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category->getCategoryName()) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div>
        <label for="description">Description:</label>
        <input type="text" name="description" id="description"
            <?= isset($expense) ? 'value="' . htmlspecialchars($expense->getDescription()) . '"' : '' ?>
            required>
    </div>

    <div>
        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01"
            <?= isset($expense) ? 'value="' . htmlspecialchars($expense->getAmount()) . '"' : '' ?>
            required>
    </div>

    <div>
        <label for="expense_date">Expense Date:</label>
        <input type="date" name="expense_date" id="expense_date"
            <?= isset($expense) ? 'value="' . htmlspecialchars($expense->getExpenseDate()->format('Y-m-d')) . '"' : '' ?>
            required>
    </div>

    <button type="submit"><?= isset($expense) ? 'Update Expense' : 'Add Expense' ?></button>
</form>