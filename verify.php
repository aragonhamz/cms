<?php
require('top.inc.php'); // (If you are using a template like your other pages)

if (isset($_GET['app_id']) && !empty($_GET['app_id'])) {
    $application_id = trim($_GET['app_id']);

    $stmt = $con->prepare("SELECT * FROM mdcer WHERE application_id = ?");
    $stmt->bind_param("s", $application_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $application = $result->fetch_assoc();
        } else {
            $error = "Application ID not found.";
        }
    } else {
        $error = "Something went wrong. Please try again.";
    }
    $stmt->close();
} else {
    $error = "Invalid request.";
}
?>

<div class="content pb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger mt-4">
                        <?php echo $error; ?>
                    </div>
                <?php } else { ?>
                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h4 class="m-0 font-weight-bold text-primary">Application Verification</h4>
                        </div>
                        <div class="card-body">
                        <?php if ($application['payment'] == 'Success') { ?>
                            <div class="alert alert-success">
                                ✅ This application is verified and payment is successful.
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning">
                                ⚠️ This application exists but payment is pending or failed.
                            </div>
                        <?php } ?>

                            <p><strong>Application ID:</strong> <?php echo htmlspecialchars($application['application_id']); ?></p>
                            <p><strong>Certificate ID:</strong> <?php echo htmlspecialchars($application['cer_id']); ?></p>
                            <p><strong>Applied On:</strong> <?php echo htmlspecialchars($application['appliedon']); ?></p>
                            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($application['payment']); ?></p>
                            <p><strong>Application Status:</strong> <?php echo htmlspecialchars($application['status']); ?></p>
                            <p><strong>Remarks:</strong> <?php echo htmlspecialchars($application['msgs']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require('footer.inc.php'); ?>
