<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var regId = $(this).data('regid');
                var eventId = $(this).data('eventid');
                if (confirm('Are you sure you want to delete this event?')) {
                    window.location.href = '<?php echo site_url('event/delete_db/'); ?>' + regId + '/' + eventId;
                }
            });
        });
    </script>
</head>
<body>
    <div>
        <h2>Manage Events</h2>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <table class="table table-bordered table-sm table-hover table-striped">
            <thead>
                <tr class='table-primary'>
                    <?php if (!empty($events)): ?>
                        <?php foreach (array_keys((array)$events[0]) as $column): ?>
                            <?php if ($column != 'reg_id'): ?>
                                <th><?php echo ucfirst($column); ?></th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <form action="<?php echo site_url('event/update_db'); ?>" method="post">
                                <?php foreach ($event as $key => $value): ?>
                                    <?php if ($key != 'reg_id'): ?>
                                        <td><input type="text" name="<?php echo $key; ?>" class="form-control" value="<?php echo html_escape($value); ?>"></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <td nowrap>
                                    <input type="hidden" name="id" value="<?php echo $event->reg_id; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-regid="<?php echo $event->reg_id; ?>" data-eventid="<?php echo $event->event_id; ?>"><i class="fas fa-trash"></i> </button>
                                </form>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="99">No events found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>