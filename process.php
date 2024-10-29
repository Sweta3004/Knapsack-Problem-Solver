<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Change if you have set a password for root
$dbname = "knapsack_solver";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $knapsack_type = $_POST['knapsack_type'];
    $weights = $_POST['weights'];
    $values = $_POST['values'];
    $capacity = (int)$_POST['capacity'];

    // Insert input data into knapsack_inputs table
    $stmt = $conn->prepare("INSERT INTO knapsack_inputs (knapsack_type, weights, `values`, capacity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $knapsack_type, $weights, $values, $capacity);
    $stmt->execute();
    $input_id = $stmt->insert_id; // Get the last inserted ID
    $stmt->close();

    // Convert comma-separated strings to arrays
    $weights = array_map('intval', explode(',', $weights));
    $values = array_map('intval', explode(',', $values));

    // Calculate result based on knapsack type
    $result = [];
    if ($knapsack_type === "Fractional Knapsack") {
        $result = fractionalKnapsack($weights, $values, $capacity);
    } else if ($knapsack_type === "0-1 Knapsack") {
        $result = zeroOneKnapsack($weights, $values, $capacity);
    }

    // Convert result to JSON for storage
    $result_json = json_encode($result);

    // Save the result to knapsack_results table
    $stmt = $conn->prepare("INSERT INTO knapsack_results (input_id, result) VALUES (?, ?)");
    $stmt->bind_param("is", $input_id, $result_json);
    $stmt->execute();
    $stmt->close();

    // Close connection
    $conn->close();

    // Display the result
    // Display the result
echo '<div class="result-display" style="text-align: center; margin-top: 20px;">'; // Center align the container
echo "<h2>Knapsack Solution</h2>";
echo '<table style="width:100%; border-collapse: collapse; border: 1px solid black;">'; // Use border-collapse for better alignment
echo '<thead>';
echo '<tr>';
echo '<th style="border: 1px solid black; padding: 8px;">Type</th>'; // Add padding for better spacing
echo '<th style="border: 1px solid black; padding: 8px;">Total Value</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
echo '<center><td style="border: 1px solid black; padding: 8px;">' . htmlspecialchars($knapsack_type) . '</td></center>';
echo '<center><td style="border: 1px solid black; padding: 8px;">' . htmlspecialchars($result['total_value']) . '</td></center>';
echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</div>';
}

// Function for Fractional Knapsack
function fractionalKnapsack($weights, $values, $capacity) {
    $items = [];
    $n = count($weights);

    // Create an array of items with value-to-weight ratio
    for ($i = 0; $i < $n; $i++) {
        $items[] = [
            'weight' => $weights[$i],
            'value' => $values[$i],
            'ratio' => $values[$i] / $weights[$i]
        ];
    }

    // Sort items by value-to-weight ratio in descending order
    usort($items, function($a, $b) {
        return $b['ratio'] <=> $a['ratio'];
    });

    $total_value = 0.0;
    foreach ($items as $item) {
        if ($capacity > 0 && $item['weight'] <= $capacity) {
            $capacity -= $item['weight'];
            $total_value += $item['value'];
        } else {
            $total_value += $item['value'] * ($capacity / $item['weight']);
            break;
        }
    }

    return ['total_value' => $total_value];
}

// Function for 0/1 Knapsack
function zeroOneKnapsack($weights, $values, $capacity) {
    $n = count($weights);
    $dp = array_fill(0, $n + 1, array_fill(0, $capacity + 1, 0));

    // Build table dp[][] in a bottom-up approach
    for ($i = 1; $i <= $n; $i++) {
        for ($w = 0; $w <= $capacity; $w++) {
            if ($weights[$i - 1] <= $w) {
                $dp[$i][$w] = max($values[$i - 1] + $dp[$i - 1][$w - $weights[$i - 1]], $dp[$i - 1][$w]);
            } else {
                $dp[$i][$w] = $dp[$i - 1][$w];
            }
        }
    }

    return ['total_value' => $dp[$n][$capacity]];
}
?>