<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Reference Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
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
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #45a049;
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required]');

            form.addEventListener('submit', (event) => {
                let valid = true;
                inputs.forEach(input => {
                    const feedback = input.nextElementSibling;
                    if (!input.value.trim()) {
                        feedback.textContent = `${input.name} is required.`;
                        valid = false;
                    } else {
                        feedback.textContent = '';
                    }
                });
                if (!valid) event.preventDefault();
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Research Reference Manager</h1>
    </header>

    <main>
        <section>
            <h2>Update the Reference</h2>
            <form action="{{ route('references.update', $reference->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- This is important to simulate a PUT request -->

                <label for="title">Title:</label>
                <input type="text" name="title" value="{{ old('title', $reference->title) }}" required>

                <label for="authors">Authors:</label>
                <input type="text" name="authors" value="{{ old('authors', $reference->authors) }}" required>

                <label for="journal">Journal/Conference:</label>
                <input type="text" name="journal" value="{{ old('journal', $reference->journal) }}">

                <label for="doi">DOI:</label>
                <input type="text" name="doi" value="{{ old('doi', $reference->doi) }}">

                <label for="year">Year:</label>
                <input type="number" name="year" value="{{ old('year', $reference->year) }}" required>


                <button type="submit">Update Reference</button>
            </form>
        </section>
    </main>
</body>
</html>
