use GuzzleHttp\Client;

class OpenLibraryClient {
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function obtenerDescripcion($isbn) {
        $response = $this->client->request('GET', 'https://openlibrary.org/api/books', [
            'query' => [
                'bibkeys' => "ISBN:$isbn",
                'format' => 'json',
                'jscmd' => 'data'
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data["ISBN:$isbn"]['description'] ?? 'Descripción no disponible';
    }
}
