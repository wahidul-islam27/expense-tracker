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

<h1>Your Profile</h1>

<form method="POST" action='/expense-tracker/public/profile'>

    <div>
        <label for="username">User Name:</label>
        <input type="text" name="username" id="username" value="<?= $user->getUsername() ?>" required>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <label for="monthlyIncome">Monthly Income</label>
        <input type="number" name="monthlyIncome" id="monthlyIncome" value="<?= $user->getMonthlyIncome() ?>" required>
    </div>

    <button type="submit">Update</button>
</form>