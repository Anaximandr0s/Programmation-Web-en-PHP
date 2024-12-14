<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Reference Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
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

        /* Style for the auth links */
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

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #ffffff;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button {
            background-color: #7B6ADA;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #7B6ADA;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed; /* Ensures columns are of equal width */
            word-wrap: break-word; /* Ensures long text wraps and doesn't overflow */
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
            word-wrap: break-word; /* Ensures content doesn't overflow */
        }

        th {
            background-color: #7B6ADA;
            color: white;
        }

        /* Add horizontal scroll for tables that overflow */
        .table-container {
            overflow-x: auto;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #7B6ADA;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #7B6ADA;
        }

        .form-feedback {
            color: red;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            form input, form button {
                font-size: 14px;
            }

            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Research Reference Manager</h1>

        <!-- Authentication Links -->
        @if (Auth::check())
            <div class="auth-links">
                <a href="">Profile</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @else
            <div class="auth-links">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            </div>
        @endif
    </header>

    <main>
        <section>
            <!-- Search Bar Form -->
            <form id="searchForm">
                <label for="search-query">Enter DOI or Article Title:</label>
                <input type="text" id="search-query" name="search-query" placeholder="e.g., 10.1109/5.771073 or Article Title" required>
                <button type="submit">Search</button>
            </form>

            <!-- Search Results -->
            <div id="search-results">
                <!-- Results will be displayed here -->
            </div>
        </section>
    </main>

    <script>
        // Handle search form submission
        document.getElementById('searchForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const query = document.getElementById('search-query').value;
            const resultsDiv = document.getElementById('search-results');
            resultsDiv.innerHTML = ''; // Clear previous results

            try {
                // Fetch article data from CrossRef API
                const response = await fetch(`https://api.crossref.org/works/${query}`);
                const data = await response.json();

                if (data.message) {
                    const { title, author, 'container-title': journal, 'published-print': { 'date-parts': [[year]] }, abstract, DOI, publisher, 'subject': topics, 'link': links } = data.message;

                    const authors = author.map(a => `${a.given} ${a.family}`).join(', ');
                    const topicsText = topics ? topics.join(', ') : 'No subjects available';
                    const abstractText = abstract ? abstract : 'No abstract available';
                    const imageUrl = links && links.length > 0 ? links[0].URL : null;

                    resultsDiv.innerHTML = `
                        <h2>Article Details</h2>
                        <p><strong>Title:</strong> ${title}</p>
                        <p><strong>Authors:</strong> ${authors}</p>
                        <p><strong>Journal:</strong> ${journal}</p>
                        <p><strong>Year:</strong> ${year}</p>
                        <p><strong>DOI:</strong> <a href="https://doi.org/${DOI}" target="_blank">${DOI}</a></p>
                        <p><strong>Publisher:</strong> ${publisher}</p>
                    `;
                    if (imageUrl) {
                        resultsDiv.innerHTML += `<p><strong>Image:</strong> <img src="${imageUrl}" alt="Image related to the article" width="300"></p>`;
                    }

                } else {
                    resultsDiv.innerHTML = '<p>No results found. Please try again.</p>';
                }
            } catch (error) {
                resultsDiv.innerHTML = '<p>Error fetching data. Please try again later.</p>';
            }
        });
    </script>
</body>
</html>
