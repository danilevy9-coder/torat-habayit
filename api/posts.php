<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// Paths
$dataDir = __DIR__ . '/../data';
$dataFile = $dataDir . '/posts.json';
$uploadsDir = __DIR__ . '/../images/uploads/';

if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

// Default initial state matching frontend
$defaults = [
    [
        "id" => "1",
        "title" => "Brain Death in Halacha: Understanding the Contemporary Debate",
        "slug" => "brain-death-in-halacha-contemporary-debate",
        "excerpt" => "The question of brain death and organ donation remains one of the most debated issues in contemporary halachic literature. We examine the key positions and their practical implications.",
        "content" => "<p>The question of brain death and organ donation remains one of the most debated issues in contemporary halachic literature. Over the past several decades, the advancement of medical technology has forced poskim to grapple with questions that previous generations could never have anticipated.</p><p>The crux of the debate revolves around a single fundamental question: does brain death constitute halachic death (mitat ha-nes)? Traditional halachic sources, drawing on the Gemara in Yoma 85a, define death primarily through the cessation of breathing. The question is whether this applies only to irreversible cessation of respiratory function at the lung level, or whether it encompasses the brain's capacity to control breathing.</p><p>Rabbi Moshe Feinstein and Rabbi Shlomo Zalman Auerbach took differing views on this question, and their responsa have become the foundational texts for subsequent discussion. At the Torat Habayit Institute, we have worked to synthesize these positions into practical halachic guidance for the medical community and for families facing these most difficult decisions.</p>",
        "category" => "Halacha",
        "author" => "Rabbi Yehuda Finchas",
        "date" => "2026-03-10",
        "image" => "images/books/BD EN Book.jpg",
        "status" => "published"
    ],
    [
        "id" => "2",
        "title" => "Medications on Yom Kippur: A Practical Halachic Guide",
        "slug" => "medications-yom-kippur-practical-guide",
        "excerpt" => "As Yom Kippur approaches, many patients with chronic conditions ask whether they may take their medications on the fast. Here is a clear framework for common situations.",
        "content" => "<p>Each year as Yom Kippur approaches, the phones at the Torat Habayit Institute ring with the same urgent question: \"May I take my medication on the fast?\" The answer, like so much in halacha, depends on the details of the case.</p><p>The Torah prohibition of eating and drinking on Yom Kippur applies to quantities of food (shiur achila) and liquid. However, the halachic analysis of medications involves several additional layers: Is the substance food-like? Is it bitter? Is it swallowed in pill form? Is the condition life-threatening or potentially dangerous?</p><p>The general framework we use at the Institute is as follows: Medications that are essential for life or that, if missed, would pose a genuine health risk should be taken even on Yom Kippur, as pikuach nefesh overrides all other considerations. Where possible, liquid medications should be made bitter before taking them. Pills may be swallowed in most cases without halachic concern.</p>",
        "category" => "Practical Halacha",
        "author" => "Torat Habayit Institute",
        "date" => "2026-02-20",
        "image" => "images/books/Chagim Book.jpg",
        "status" => "published"
    ],
    [
        "id" => "3",
        "title" => "New Seminar Announcement: Genetics and Jewish Law 2026",
        "slug" => "genetics-jewish-law-seminar-2026",
        "excerpt" => "We are proud to announce our upcoming international seminar series on Genetics and Jewish Law, coming to London, New York, and Tel Aviv in the spring of 2026.",
        "content" => "<p>We are proud to announce our upcoming international seminar series on Genetics and Jewish Law, coming to London, New York, and Tel Aviv in the spring of 2026.</p><p>The rapid advancement of genetic science has created a new frontier for halachic inquiry. Carrier screening, preimplantation genetic diagnosis (PGD), whole-genome sequencing, and BRCA gene testing all raise profound questions about the nature of medical knowledge, the obligations of disclosure, and the sanctity of human life.</p><p>This seminar series will bring together leading halachic authorities and medical geneticists to explore these questions in depth. Topics will include: the halachic status of genetic information, obligations of disclosure to family members, the permissibility of genetic selection, and the ethics of predictive testing in minors.</p><p>Registration is now open. Spaces are limited. Contact us to reserve your place.</p>",
        "category" => "Announcements",
        "author" => "Torat Habayit Institute",
        "date" => "2026-01-15",
        "image" => "images/gallery/Genetics Hendon.jpeg",
        "status" => "published"
    ]
];

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode($defaults, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$posts = json_decode(file_get_contents($dataFile), true);
if (!is_array($posts)) $posts = [];

function handleImage($base64Data, $uploadsDir) {
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
        $encodedData = substr($base64Data, strpos($base64Data, ',') + 1);
        $ext = strtolower($type[1]);
        if (!in_array($ext, [ 'jpg', 'jpeg', 'gif', 'png', 'webp' ])) {
            return ''; 
        }
        $decodedData = base64_decode($encodedData);
        if ($decodedData === false) return '';
        
        $filename = uniqid('img_') . '.' . $ext;
        file_put_contents($uploadsDir . $filename, $decodedData);
        return 'images/uploads/' . $filename;
    }
    return $base64Data; 
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    if(function_exists('iconv')) $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

if ($method === 'GET') {
    echo json_encode($posts);
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400); 
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    if (isset($input['image']) && strpos($input['image'], 'data:image') === 0) {
        $input['image'] = handleImage($input['image'], $uploadsDir);
    }
    
    $newPost = [
        "id" => (string)microtime(true),
        "title" => $input['title'] ?? '',
        "slug" => slugify($input['title'] ?? 'untitled'),
        "excerpt" => $input['excerpt'] ?? '',
        "content" => $input['content'] ?? '',
        "category" => $input['category'] ?? 'General',
        "author" => $input['author'] ?? 'Torat Habayit Institute',
        "date" => $input['date'] ?? date('Y-m-d'),
        "image" => $input['image'] ?? '',
        "status" => $input['status'] ?? 'draft'
    ];
    
    array_unshift($posts, $newPost);
    file_put_contents($dataFile, json_encode($posts, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'post' => $newPost]);
    
} elseif ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['id'])) {
        http_response_code(400); 
        echo json_encode(['error' => 'Invalid Request']);
        exit;
    }

    if (isset($input['image']) && strpos($input['image'], 'data:image') === 0) {
        $input['image'] = handleImage($input['image'], $uploadsDir);
    }
    
    $updatedPost = null;
    foreach ($posts as &$p) {
        if ($p['id'] == $input['id']) {
            $p['title'] = isset($input['title']) ? $input['title'] : $p['title'];
            if (isset($input['title'])) $p['slug'] = slugify($input['title']);
            $p['excerpt'] = isset($input['excerpt']) ? $input['excerpt'] : $p['excerpt'];
            $p['content'] = isset($input['content']) ? $input['content'] : $p['content'];
            $p['category'] = isset($input['category']) ? $input['category'] : $p['category'];
            $p['author'] = isset($input['author']) ? $input['author'] : $p['author'];
            $p['date'] = isset($input['date']) ? $input['date'] : $p['date'];
            $p['status'] = isset($input['status']) ? $input['status'] : $p['status'];
            if (isset($input['image'])) $p['image'] = $input['image'];
            
            $p['updatedAt'] = date('c');
            $updatedPost = $p;
            break;
        }
    }
    
    if ($updatedPost) {
        file_put_contents($dataFile, json_encode($posts, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'post' => $updatedPost]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Post not found']);
    }
    
} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? '';
    if (!$id) {
        http_response_code(400);
        exit;
    }
    
    $initialCount = count($posts);
    $posts = array_filter($posts, function($p) use ($id) { return $p['id'] != $id; });
    $posts = array_values($posts);
    
    if (count($posts) < $initialCount) {
        file_put_contents($dataFile, json_encode($posts, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Post not found']);
    }
} else {
    http_response_code(405);
}
?>
