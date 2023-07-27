<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[AsCommand(
    name: 'app:mercure:run',
    description: 'Add a short description for your command',
)]
class MercureRunCommand extends Command
{
    public function __construct(
        private readonly HubInterface $hub,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('interval', InputArgument::OPTIONAL, 'Interval to publish (default 1 second)', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $interval = $input->getArgument('interval');

        $io = new SymfonyStyle($input, $output);
        $io->note(sprintf('Publishing every %d seconds', $interval));

        $maxRun = 3600;
        $start  = $current = time();
        $end    = $start + $maxRun;

        $nextTick = $start + $interval;

        while ($current < $end) {
            usleep(100000);
            $current = time();
            if ($current >= $nextTick) {
                $encode = json_encode(['color' => sprintf('rgb(%d, %d, %d)', random_int(100, 255), random_int(100, 255), random_int(100, 255))]);
                $update = new Update(
                    'https://sandbox-sf6.ddev.site/channel',
                    $encode
                );

                $this->hub->publish($update);
                $io->comment('Pushed new update: ' . $encode);
                $nextTick = $nextTick + $interval;
            }
        }

        $io->success('Mercure publishing has stopped running after a max execution time');

        return Command::SUCCESS;
    }
}
