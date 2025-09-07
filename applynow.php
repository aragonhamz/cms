<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css">
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header class="bg-light shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="#">
                <img src="https://via.placeholder.com/100x50" alt="Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mt-4 ">
    <form id="contact-form" role="form">   
                    <div class="form-group row mb-5">
                        <!-- <label class="col-6 col-lg-4 display-4">Certificate</label>
                        <div class="col-12 col-lg-6 d-flex justify-content-center ">
                        <select id="form_need" name="need" class="form-control" required="required" data-error="Please specify your need.">
                                <option value="" selected disabled>--Select Certificate type--</option>
                                <option >Request Invoice for order</option>
                                <option >Request order status</option>
                                <option >Haven't received cashback yet</option>
                                <option >Other</option>
                            </select>
                        </div> -->
                        <label for="documentType" class="form-label">Document Type</label>
                            <select class="form-control" id="documentType" onchange="toggleFields()" required>
                                <option value="">Select Document Type</option>
                                <option value="registration">Registration Card</option>
                                <option value="degree">Degree Certificate</option>
                            </select>
                      </div>
        <div class="row">
            
        </div>   
       <div class="row bg-warning">
                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="form_name">Full Name (Capital Letters) *</label>
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required data-error="Firstname is required.">
                            
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="form-group">
                            <label for="form_name">Gender</label>
                            
                            <select id="form_need" name="need" class="form-control" required="required" data-error="Please specify your need.">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="form_email">Email *</label>
                            <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-4" id="contactField">
                        <div class="form-group">
                        <label for="contact" class="form-label" >Contact</label>
                        <input type="tel" class="form-control"  placeholder="Enter your contact number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_need">Please specify your need *</label>
                            <select id="form_need" name="need" class="form-control" required="required" data-error="Please specify your need.">
                                <option value="" selected disabled>--Select Your Issue--</option>
                                <option >Request Invoice for order</option>
                                <option >Request order status</option>
                                <option >Haven't received cashback yet</option>
                                <option >Other</option>
                            </select>
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="form_message">Message *</label>
                            <textarea id="form_message" name="message" class="form-control" placeholder="Write your message here." rows="4" required="required" data-error="Please, leave us a message."></textarea
                                >
                            </div>

                        </div>


                    <div class="col-md-12">
                        
                        <input type="submit" class="btn btn-success btn-send  pt-2 btn-block
                            " value="Send Message" >
                            <button type="submit" class="btn btn-success btn-send id="saveform">Save</button>
                    
                </div>
          
                </div>
            
    </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                document.getElementById("guidelinesCard").classList.add("show");
            }, 500);
        });

        function showForm() {
            document.getElementById("submissionForm").classList.toggle("d-block");
        }

        function toggleFields() {
            var documentType = document.getElementById("documentType").value;
            var contactField = document.getElementById("contactField");
            
            if (documentType === "degree") {
                contactField.style.display = "none";
            } else {
                contactField.style.display = "block";
            }
        }
    </script>
</body>
</html>
