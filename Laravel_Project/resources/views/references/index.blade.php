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
            <h2>Add a New Reference</h2>
            <form action="/references" method="POST">
                @csrf
                <label for="title">Title:</label>
                <input type="text" name="title" required>
                <span class="form-feedback"></span>

                <label for="authors">Authors:</label>
                <input type="text" name="authors" required>
                <span class="form-feedback"></span>

                <label for="journal">Journal/Conference:</label>
                <input type="text" name="journal">
                <span class="form-feedback"></span>

                <label for="year">Year:</label>
                <input type="number" name="year" required>
                <span class="form-feedback"></span>

                <label for="doi">DOI:</label>
                <input type="text" name="doi">
                <span class="form-feedback"></span>

                <button type="submit">Add Reference</button>
            </form>
        </section>

        <section>
            <h2>Saved References</h2>
            <!-- Make the table scrollable horizontally on smaller screens -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Journal</th>
                            <th>DOI</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($references as $ref)
                        <tr>
                            <td>{{ $ref->title }}</td>
                            <td>{{ $ref->authors }}</td>
                            <td>{{ $ref->journal }}</td>
                            <td>{{ $ref->doi }}</td>
                            <td>{{ $ref->year }}</td>
                            <td>
                                <a href="{{ route('references.edit', $ref->id) }}" class="btn btn-primary">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                <form action="{{ route('references.destroy', $ref->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this reference?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <h2>Export</h2>
            <a href="{{ route('export', ['format' => 'bibtex']) }}">Download BibTeX File</a>
            <a href="{{ route('export', ['format' => 'csv']) }}">Download CSV File</a>
            <a href="{{ route('export', ['format' => 'json']) }}">Download JSON File</a>
            <a href="{{ route('export', ['format' => 'endnote']) }}">Download EndNote File</a>
        </section>
            </main>
</body>
</html>
