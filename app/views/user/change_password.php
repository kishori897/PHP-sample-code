
<h2>Change Password</h2>
<form method="POST">
    <div class="form-group">
        <label for="oldpwd">Old Password:</label>
        <input type="password" id="oldpwd" name="oldpwd" required>
        <?php if (!empty($errors['oldpwd'])): ?>
            <div class="error"><?php echo $errors['oldpwd']; ?></div>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="newpwd">New Password:</label>
        <input type="password" id="newpwd" name="newpwd" required>
        <?php if (!empty($errors['newpwd'])): ?>
            <div class="error"><?php echo $errors['newpwd']; ?></div>
        <?php endif; ?>
    </div>

    <div class ="form-group">
        <label for="confirmpwd">Confirm New Password:</label>
        <input type="password" id="confirmpwd" name="confirmpwd" required>
        <?php if (!empty($errors['confirmpwd'])): ?>
            <div class="error"><?php echo $errors['confirmpwd']; ?></div>
        <?php endif; ?>
        
    </div>
    
    <div class="form-group">
        <button type="submit">Update</button>
    </div>
</form>
<a href="<?php echo BASE_URL; ?>/users" class="back-link">Back to list</a>

