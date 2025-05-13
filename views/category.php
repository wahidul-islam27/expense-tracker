<!DOCTYPE html>

<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 50%;
        border-collapse: collapse;
        margin-bottom: 20px;
        margin: auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f4f4f4;
    }

    .col-actions {
        width: 150px;
        display: flex;
        justify-content: space-evenly;
    }

    #addCategoryForm {
        display: none;
        margin-top: 20px;
        width: 50%;
        margin: auto;
    }

    #addCategoryForm input {
        padding: 8px;
        width: calc(100% - 16px);
        margin-bottom: 10px;
    }

    button {
        padding: 8px 16px;
        cursor: pointer;
        background-color: #28a745;
        border: none;
        color: white;
    }

    .btn-edit {
        background-color: #007bff;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-add {
        margin-top: 35px;
        margin-left: 225px;
    }
</style>


<h2 style="text-align: center;">Category List</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th class="col-actions">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= $category->getId() ?></td>
                <td><?= htmlspecialchars($category->getCategoryName()) ?></td>
                <td class="col-actions">
                    <form id="editBtn" action="category/update?id=<?= $category->getId() ?>" method="POST">
                        <button class="edit" type="submit">Edit</button>
                    </form>
                    <form id="deleteBtn" action="category/delete?id=<?= $category->getId() ?>" method="POST">
                        <button class="btn-delete" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php if (!isset($editCategory)): ?>
    <button id="showAddForm" class="btn-add">Add Category</button>

    <form id="addCategoryForm" method="POST" action="/expense-tracker/public/category">
        <h3>Add New Category</h3>
        <input type="text" name="category_name" placeholder="Enter Category Name" required>
        <button type="submit">Submit</button>
    </form>
<?php else: ?>

    <form id="updateCategoryForm" method="POST" action="/expense-tracker/public/category/update">
        <h3>Update Category</h3>
        <input type="text" name="category_name" placeholder="Enter Category Name" required>
        <button type="submit">Update</button>
    </form>

<?php endif; ?>

<script>
    document.getElementById('showAddForm').addEventListener('click', function() {
        document.getElementById('addCategoryForm').style.display = 'block';
        this.style.display = 'none';
    });
</script>