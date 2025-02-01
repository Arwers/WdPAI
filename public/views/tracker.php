<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="public/css/tracker.css">
    <script src="public/js/tracker.js" defer></script>
    <script src="https://kit.fontawesome.com/a8e508b44f.js" crossorigin="anonymous"></script>
    <title>Tracker</title>
</head>
<body>
<div class="base-container">
    <nav>
        <img src="public/img/logo.svg" alt="Logo">
        <ul>
            <li>
                <i class="fa-solid fa-chart-simple"></i>
                <a href="tracker" class="button">Tracker</a>
            </li>
            <li>
                <i class="fa-solid fa-user"></i>
                <a href="#" class="button">Account</a>
            </li>
            <li>
                <i class="fa-solid fa-gear"></i>
                <a href="#" class="button">Settings</a>
            </li>
            <li>
                <i class="fa-solid fa-right-from-bracket"></i>
                <a href="logout" class="button">Log out</a>
            </li>
        </ul>
    </nav>
    <main>
        <section class="tracker">
            <h1 class="monthly-total">
                Total spent: $<?= isset($totalExpenses) ? number_format($totalExpenses, 2) : "0.00" ?>
            </h1>


            <form class="expense-form expense-block" id="expense-form" method="post" action="addExpense">
                <label for="date">Date:</label>
                <input id="date" type="date" name="date" value="<?= date('Y-m-d') ?>" required />

                <label for="name">Name:</label>
                <input id="name" type="text" name="name" placeholder="Expense name" required />

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="groceries">Groceries</option>
                    <option value="utilities">Utilities</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="transportation">Transportation</option>
                    <option value="other">Other</option>
                </select>

                <label for="price">Price:</label>
                <input id="price" type="number" name="price" step="0.01" placeholder="0.00" required />

                <button type="submit">Add Expense</button>
            </form>

            <div class="expense-list expense-block">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody id="expense-table-body">
                    <?php if (isset($expenses) && count($expenses) > 0): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?= htmlspecialchars($expense['date']) ?></td>
                                <td><?= htmlspecialchars($expense['expense_name']) ?></td>
                                <td><?= htmlspecialchars($expense['type_name']) ?></td>
                                <td>$<?= number_format($expense['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No expenses found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
