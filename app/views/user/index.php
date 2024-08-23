
<?php if (isset($_GET['success']) ): ?>
    <div class="success-message">
        <?php echo $_GET['success'];?>
    </div>
<?php endif; ?>
<h2>Users List</h2>
<a href="<?php echo BASE_URL; ?>/users/create">Add User</a>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td class="actions">
                    <a href="<?php echo BASE_URL; ?>/users/edit?id=<?php echo $user['id']; ?>" class="edit">Edit</a>
                    <a href="<?php echo BASE_URL; ?>/users/change-password?id=<?php echo $user['id']; ?>" class="edit">Change password</a>
                    <a href="<?php echo BASE_URL; ?>/users/delete?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if ($totalPages > 1): ?>
    <ul class="pagination">
        <!-- Previous Button -->
        <li>
            <a href="<?php echo $page > 1 ? BASE_URL . '/users?page=' . ($page - 1) : '#'; ?>" 
            class="<?php echo $page <= 1 ? 'disabled' : ''; ?>">
            Previous
            </a>
        </li>

        <!-- Page Number Links -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>/users?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next Button -->
        <li>
            <a href="<?php echo $page < $totalPages ? BASE_URL . '/users?page=' . ($page + 1) : '#'; ?>" 
            class="<?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
            Next
            </a>
        </li>
    </ul>
<?php endif; ?>



