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

<form method="POST" action='/expense-tracker/public/login'>

    <div style="font-size: 30px; text-align: center; font-weight: 600;">Login</div>

    <div>
        <label for="username">User Name:</label>
        <input type="text" name="username" id="username" required>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div><a href="/expense-tracker/public/forget">Forget password?</a></div>


    <button type="submit">Login</button>

    <div style="text-align: center;">
        Don't have account?
        <a href="/expense-tracker/public/register">Sign up</a>
    </div>
</form>