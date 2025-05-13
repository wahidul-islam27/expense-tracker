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

    input {
        width: 95%;
        flex: 2;
        padding: 8px;
        font-size: 1rem;
        margin-top: 12px;
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

<form method="POST" action='/expense-tracker/public/confirm'>

    <div style="font-size: 30px; text-align: center; font-weight: 600;">Confirmation Code</div>

    <div>
        <label for="code">Please enter the code:</label>
        <input type="text" name="code" id="code" required>
    </div>

    <button type="submit">Confirm</button>
</form>