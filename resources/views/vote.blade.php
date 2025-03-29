<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Votação</title>
    <!-- Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">
    <!-- Cabeçalho/hero -->
    <div class="bg-blue-600 text-white text-center py-8 mb-12">
        <h1 class="text-3xl font-bold">Sistema de Votação</h1>
        <p class="mt-2 text-lg">Empresa: {{ $company->name }}</p>
    </div>

    <!-- Container centralizado -->
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <!-- Mensagens de feedback -->
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('vote.submit', $company->hash) }}">
            @csrf
            <div class="mb-4">
                <label for="slate_id" class="block mb-2 text-gray-700 font-medium">
                    Selecione a Chapa:
                </label>
                <select name="slate_id" id="slate_id" required
                        class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:ring-blue-500">
                    <option value="">-- Escolha --</option>
                    @foreach ($chapas as $chapa)
                        <option value="{{ $chapa->id }}">{{ $chapa->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 rounded text-white font-bold bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Votar
            </button>
        </form>
    </div>
</body>
</html>
