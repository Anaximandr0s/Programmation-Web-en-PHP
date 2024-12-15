<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Reference Manager</title>
    <link rel="icon" type="image/png" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #7B6ADA;
            color: white;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }

        .auth-links {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .auth-links a {
            margin-left: 15px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #45a049;
        }

        .services {
            margin: 20px;
        }

        .service-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .service-item:hover {
            transform: translateY(-10px);
        }

        .service-item h4 {
            font-size: 1.5rem;
            color: #7B6ADA;
        }

        .service-item p {
            font-size: 1rem;
            color: #555;
        }

        .icon {
    background-color: purple;
    padding: 15px; /* Adjust padding to control space around the image */
    border-radius: 8px; /* Optional to give rounded corners */
    width: 60px; /* Set the desired width */
    height: 60px; /* Set the desired height */
    display: flex;
    justify-content: center; /* Center image horizontally */
    align-items: center; /* Center image vertically */
}

.icon img {
    max-width: 50px; /* Set max width of the image */
    max-height: 50px; /* Set max height of the image */
    width: auto;
    height: auto;
}


        .service-item .main-button a {
            text-decoration: none;
            background-color: #7B6ADA;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .service-item .main-button a:hover {
            background-color: #5A4AA1;
        }

        .service-item .icon {
            margin-bottom: 15px;
        }

        .main-button {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Research Services</h1>

    @if (Auth::check())
    <div class="auth-links">
        <a href="">Profile</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    @endif
</header>

<main>
    <section class="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="icon" style="background-color: purple;">
                            <img src="assets/images/service-01.png" alt="Reference Manager" />
                        </div>
                        <div class="main-content">
                            <h4>Reference Manager</h4>
                            <p>A comprehensive tool to efficiently manage and organize your research references, including support for importing, exporting, and formatting citations in multiple styles like APA, MLA, and BibTeX.</p>
                            <div class="main-button">
                                <a href="{{ route('RefManager') }}" target="_blank">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="icon" style="background-color: purple;">
                            <img src="assets/images/service-02.png" alt="Research Organizer" />
                        </div>
                        <div class="main-content">
                            <h4>DOI Explorer</h4>
                            <p>DOI Explorer is an intuitive online tool designed for researchers to effortlessly search for academic articles, papers, and resources using their DOI (Digital Object Identifier) or titles.</p>
                            <div class="main-button">
                                <a href="{{ route('search') }}" target="_blank">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="icon" style="background-color: purple;">
                            <img src="assets/images/service-03.png" alt="Publication Tracker" />
                        </div>
                        <div class="main-content">
                            <h4>Publication Tracker</h4>
                            <p>Stay on top of your submissions with a built-in tracker for academic publications. Monitor the status of your work across journals and conferences, including deadlines, reviews, and updates.</p>
                            <div class="main-button">
                                <a href="">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
</html>
