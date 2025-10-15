<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use TVMaze\Client\TVMazeClient;
use TVMaze\Exception\TVMazeException;

echo "TVMaze PHP Client - Attribution Usage Example\n";
echo "==============================================\n\n";

try {
    // Create a client instance
    $client = TVMazeClient::create('TVMaze-PHP-Client-Attribution/1.0');

    // Search for a show
    echo "1. Searching for 'The Office'...\n";
    $shows = $client->searchShows('the office');

    if (!empty($shows)) {
        $firstShow = $shows[0]['show'];
        echo "   Found: {$firstShow->name}\n";
        echo "   Summary: {$firstShow->getTruncatedSummary(100)}\n\n";

        // Get first episode
        echo "2. Getting first episode...\n";
        $episodes = $client->getShowEpisodes($firstShow->id);

        if (!empty($episodes)) {
            $firstEpisode = $episodes[0];
            echo "   Episode: {$firstEpisode->getFormattedTitle()}\n";
            echo "   Summary: {$firstEpisode->getTruncatedSummary(100)}\n\n";
        }

        // Demonstrate different attribution methods
        echo "3. Attribution Examples:\n\n";

        echo "   HTML Attribution:\n";
        echo '   ' . $client->getAttributionHtml() . "\n\n";

        echo "   Plain Text Attribution:\n";
        echo '   ' . $client->getAttributionText() . "\n\n";

        echo "   Markdown Attribution:\n";
        echo '   ' . $client->getAttributionMarkdown() . "\n\n";

        echo "   Detailed Attribution:\n";
        echo '   ' . $client->getDetailedAttributionHtml() . "\n\n";

        echo "   Show-specific Attribution:\n";
        echo '   ' . $firstShow->getAttributionHtml() . "\n\n";

        if (!empty($episodes)) {
            echo "   Episode-specific Attribution:\n";
            echo '   ' . $firstEpisode->getAttributionHtml() . "\n\n";
        }

        // Example of how to use in a web application
        echo "4. Example for Web Application:\n";
        echo "   <div class=\"show-info\">\n";
        echo "       <h2>{$firstShow->name}</h2>\n";
        echo "       <p>{$firstShow->getTruncatedSummary()}</p>\n";
        echo "       {$client->getAttributionHtml()}\n";
        echo "   </div>\n\n";

        // Example for console application
        echo "5. Example for Console Application:\n";
        echo "   Show: {$firstShow->name}\n";
        echo "   Summary: {$firstShow->getTruncatedSummary(100)}\n";
        echo "   {$client->getAttributionText()}\n\n";

        // Example for Markdown documentation
        echo "6. Example for Markdown Documentation:\n";
        echo "   ## {$firstShow->name}\n";
        echo "   {$firstShow->getTruncatedSummary(100)}\n";
        echo "   \n";
        echo "   {$client->getAttributionMarkdown()}\n\n";
    }

    echo "✅ Attribution example completed successfully!\n";
    echo "\n📋 License Compliance:\n";
    echo "   - CC BY-SA 4.0 license requires attribution\n";
    echo "   - Always include TVMaze attribution when displaying data\n";
    echo "   - Use getAttributionHtml() for web applications\n";
    echo "   - Use getAttributionText() for console applications\n";
    echo "   - Use getAttributionMarkdown() for documentation\n";
} catch (TVMazeException $e) {
    echo '❌ TVMaze API Error: ' . $e->getMessage() . "\n";
    echo '   Code: ' . $e->getCode() . "\n";
} catch (Exception $e) {
    echo '❌ Error: ' . $e->getMessage() . "\n";
}
