<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use TVMaze\Client\TVMazeClient;
use TVMaze\Exception\TVMazeException;

echo "TVMaze PHP Client - Basic Usage Example\n";
echo "=======================================\n\n";

try {
    // Create a client instance
    $client = TVMazeClient::create('TVMaze-PHP-Client-Example/1.0');

    // Search for a show
    echo "1. Searching for 'Breaking Bad'...\n";
    $shows = $client->searchShows('breaking bad');

    if (!empty($shows)) {
        $firstShow = $shows[0]['show'];
        echo "   Found: {$firstShow->name} (Score: {$shows[0]['score']})\n";
        echo "   ID: {$firstShow->id}\n\n";

        // Get detailed show information
        echo "2. Getting detailed information for Breaking Bad...\n";
        $show = $client->getShow($firstShow->id);
        echo "   Name: {$show->name}\n";
        echo "   Status: {$show->status}\n";
        echo "   Premiered: {$show->premiered}\n";
        echo "   Runtime: {$show->runtime} minutes\n";
        echo '   Genres: ' . implode(', ', $show->genres) . "\n";

        if ($show->rating && $show->rating->average) {
            echo "   Rating: {$show->rating->average}/10\n";
        }

        if ($show->summary) {
            echo "   Summary: {$show->getTruncatedSummary()}\n";
        }
        echo "\n";

        // Get first few episodes
        echo "3. Getting first 3 episodes...\n";
        $episodes = $client->getShowEpisodes($firstShow->id);
        $firstThree = array_slice($episodes, 0, 3);

        foreach ($firstThree as $episode) {
            echo "   {$episode->getFormattedTitle()}";
            if ($episode->airdate) {
                echo " ({$episode->airdate})";
            }
            echo "\n";
        }
        echo "\n";

        // Search for cast members
        echo "4. Getting cast information...\n";
        $cast = $client->getShowCast($firstShow->id);
        $firstThreeCast = array_slice($cast, 0, 3);

        foreach ($firstThreeCast as $castMember) {
            $person = $castMember['person'];
            $character = $castMember['character'];
            echo "   {$person->name} as {$character->name}\n";
        }
        echo "\n";
    }

    // Search for a person
    echo "5. Searching for 'Bryan Cranston'...\n";
    $people = $client->searchPeople('bryan cranston');

    if (!empty($people)) {
        $firstPerson = $people[0]['person'];
        echo "   Found: {$firstPerson->name} (Score: {$people[0]['score']})\n";
        echo "   ID: {$firstPerson->id}\n\n";

        // Get detailed person information
        echo "6. Getting detailed information for Bryan Cranston...\n";
        $person = $client->getPerson($firstPerson->id);
        echo "   Name: {$person->name}\n";
        if ($person->birthday) {
            echo "   Birthday: {$person->birthday}\n";
        }
        if ($person->country) {
            echo "   Country: {$person->country->name}\n";
        }
        echo "\n";
    }

    // Get today's schedule for US
    echo "7. Getting today's TV schedule for US...\n";
    $schedule = $client->getSchedule('US');
    echo '   Found ' . count($schedule) . " episodes airing today\n";

    if (!empty($schedule)) {
        echo "   First few shows:\n";
        $firstThree = array_slice($schedule, 0, 3);
        foreach ($firstThree as $episode) {
            $show = $episode['show'];
            echo "     {$show->name} - {$episode->name}";
            if ($episode->airtime) {
                echo " at {$episode->airtime}";
            }
            echo "\n";
        }
    }

    echo "\n✅ Example completed successfully!\n";
} catch (TVMazeException $e) {
    echo '❌ TVMaze API Error: ' . $e->getMessage() . "\n";
    echo '   Code: ' . $e->getCode() . "\n";
} catch (Exception $e) {
    echo '❌ Error: ' . $e->getMessage() . "\n";
}
