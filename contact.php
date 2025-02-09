<?php
include './adminDashboard/db.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['con_name'], $_POST['con_email'], $_POST['con_subject'], $_POST['con_phone'], $_POST['con_message'])) {
        
        $name = htmlspecialchars(trim($_POST['con_name']));
        $email = filter_var(trim($_POST['con_email']), FILTER_VALIDATE_EMAIL);  
        $subject = htmlspecialchars(trim($_POST['con_subject']));
        $phone = htmlspecialchars(trim($_POST['con_phone']));
        $message = htmlspecialchars(trim($_POST['con_message']));

        if ($email && !empty($name) && !empty($subject) && !empty($message)) {
            // Prepare the SQL statement
            $query = "INSERT INTO contact_messages (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)";
            
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("sssss", $name, $email, $subject, $phone, $message);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}
?>




<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Subas || Contact</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/icon/favicon.png" />

    <!-- All CSS Files -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Nivo-slider css -->
    <link rel="stylesheet" href="lib/css/nivo-slider.css" />
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="css/core.css" />
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css" />
    <!-- Theme main style -->
    <link rel="stylesheet" href="style.css" />
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css" />
    <!-- User style -->
    <link rel="stylesheet" href="css/custom.css" />

    <!-- Style customizer (Remove these two lines please) -->
    <link rel="stylesheet" href="css/style-customizer.css" />
    <link href="#" data-style="styles" rel="stylesheet" />

    <!-- Modernizr JS -->
    <!-- <script src="js/vendor/modernizr-3.11.2.min.js"></script> -->
        <!-- jquery latest version -->
        <!-- <script src="js/vendor/jquery-3.6.0.min.js"></script> -->
    <script src="js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <!-- Bootstrap framework js -->
    <script src="js/bootstrap.bundle.min.js"></script>
         <!-- jquery.nivo.slider js -->
    <script src="lib/js/jquery.nivo.slider.js"></script>
   <!-- Google Map js -->
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDacJcoyPCr-jdlP9HK93h3YKNyf710J0"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuU_0_uLMnFM-2oWod_fzC0atPZj7dHlU"></script>
    <script src="js/map.js"></script>
    <!-- All js plugins included in this file. -->
    <script src="js/plugins.js"></script>
    <!-- ajax-mail js -->
    <script src="js/ajax-mail.js"></script>
    <!-- Main js file that contents all jQuery plugins activation. -->
    <script src="js/main.js"></script>
  </head>

  <body>
    <!--[if lt IE 8]>
      <p class="browserupgrade">
        You are using an <strong>outdated</strong> browser. Please
        <a href="http://browsehappy.com/">upgrade your browser</a> to improve
        your experience.
      </p>
    <![endif]-->

    <!-- Body main wrapper start -->
    <div class="wrapper">
      <!-- START HEADER AREA -->
      <?php include("nav.php")?>

      <!-- END HEADER AREA -->
    

      <!-- BREADCRUMBS SETCTION START -->
      <div class="breadcrumbs-section plr-200 mb-80 section">
        <div class="breadcrumbs overlay-bg">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="breadcrumbs-inner">
                  <h1 class="breadcrumbs-title">Contact</h1>
                  <ul class="breadcrumb-list">
                    <li><a href="index-4.php">Home</a></li>
                    <li>Contact</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- BREADCRUMBS SETCTION END -->

      <!-- Start page content -->
      <section id="page-content" class="page-wrapper section">
        <!-- ADDRESS SECTION START -->
        <div class="address-section mb-80">
          <div class="container">
            <div class="row">
              <div class="col-md-4">
                <div class="contact-address box-shadow">
                  <i class="zmdi zmdi-pin"></i>
                  <h6>Your address goes here.</h6>
                  <h6>Your address goes here.</h6>
                </div>
              </div>
              <div class="col-md-4">
                <div class="contact-address box-shadow">
                  <i class="zmdi zmdi-phone"></i>
                  <h6><a href="tel:0123456789">0123456789</a></h6>
                  <h6><a href="tel:0123456789">0123456789</a></h6>
                </div>
              </div>
              <div class="col-md-4">
                <div class="contact-address box-shadow">
                  <i class="zmdi zmdi-email"></i>
                  <h6>demo@example.com</h6>
                  <h6>demo@example.com</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ADDRESS SECTION END -->

        <!-- GOOGLE MAP SECTION START -->
       
        <!-- GOOGLE MAP SECTION START -->
  <div class="container-fluid">
    <div class="google-map plr-185">
      <iframe 
        width="100%" 
        height="400" 
        style="border:0;" 
        loading="lazy" 
        allowfullscreen 
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=2PRV+RV8,As-Salt&output=embed">
      </iframe>
    </div>
  </div>
</div>
<!-- GOOGLE MAP SECTION END -->


<!-- GOOGLE MAP SECTION START -->
<div class="google-map-section">
  <div class="container-fluid">
    <div class="google-map plr-185">
      <div id="googleMap" style="width: 100%; height: 400px;"></div>
    </div>
  </div>
</div>
<!-- GOOGLE MAP SECTION END -->

       <!-- GOOGLE MAP SECTION END -->

        <!-- MESSAGE BOX SECTION START -->
        <div class="message-box-section mt--50 mb-80">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="message-box box-shadow white-bg">
                  <form
                    id="contact-form"
                    action="contact.php" method = "POST"
                  >
                    <div class="row">
                      <div class="col-lg-12">
                        <h4 class="blog-section-title border-left mb-30">
                          get in touch
                        </h4>
                      </div>
                      <div class="col-lg-6">
                        <input
                          type="text"
                          name="con_name"
                          placeholder="Your name here"
                        />
                      </div>
                      <div class="col-lg-6">
                        <input
                          type="text"
                          name="con_email"
                          placeholder="Your email here"
                        />
                      </div>
                      <div class="col-lg-6">
                        <input
                          type="text"
                          name="con_subject"
                          placeholder="Subject here"
                        />
                      </div>
                      <div class="col-lg-6">
                        <input
                          type="text"
                          name="con_phone"
                          placeholder="Your phone here"
                        />
                      </div>
                      <div class="col-lg-12">
                        <textarea
                          class="custom-textarea"
                          name="con_message"
                          placeholder="Message"
                        ></textarea>
                        <button
                          class="submit-btn-1 mt-30 btn-hover-1"
                          type="submit"
                        >
                          submit message
                        </button>
                      </div>
                      </div>
                      </form>
                      <?php if (!empty($successMessage)) : ?>
                     <p class="form-message"><?php echo htmlspecialchars($successMessage); ?></p>
                   <?php endif; ?>              
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- MESSAGE BOX SECTION END -->
      </section>
      <!-- End page content -->

      <!-- START FOOTER AREA -->
      <?php include("footer.php")?>
      <!-- footer area ends  -->

    <!-- Placed JS at the end of the document so the pages load faster -->


  </body>
</html>




