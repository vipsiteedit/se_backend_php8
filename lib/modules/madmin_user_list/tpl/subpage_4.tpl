<?php foreach($section->usergroups as $usergroup): ?>
    <option value="<?php echo $usergroup->id ?>" <?php if($usergroup->sel!=0): ?>selected<?php endif; ?>>
        <?php if($usergroup->name!=''): ?><?php echo $usergroup->name ?><?php else: ?> --- <?php endif; ?>
        <?php if($usergroup->title!=''): ?>(<?php echo $usergroup->title ?>)<?php endif; ?>
    </option>

<?php endforeach; ?>
