<?php
return [
        'datahandlerqueue:execute' => [
                'class' => \Ideativedigital\DataHandlerQueue\Command\ExecuteQueueCommand::class
        ],
        'datahandlerqueue:reset' => [
                'class' => \Ideativedigital\DataHandlerQueue\Command\ResetQueueCommand::class
        ]
];