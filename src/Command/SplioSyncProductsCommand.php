<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Command;

use Dridialaa\SyliusSplioPlugin\Splio\Product\SplioProductSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'splio:sync:products', description: 'Synchronize Sylius products to Splio.')]
final class SplioSyncProductsCommand extends Command
{
    public function __construct(private readonly SplioProductSynchronizer $productSynchronizer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Maximum number of products to synchronize.')
            ->addOption('offset', null, InputOption::VALUE_REQUIRED, 'Offset used to paginate products.', 0)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Build payloads without calling Splio.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Run even if product synchronization is disabled in admin settings.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = null !== $input->getOption('limit') ? (int) $input->getOption('limit') : null;
        $offset = (int) $input->getOption('offset');
        $dryRun = (bool) $input->getOption('dry-run');
        $force = (bool) $input->getOption('force');

        $result = $this->productSynchronizer->synchronize($limit, $offset, $dryRun, $force);

        $io->table(['Processed', 'Succeeded', 'Failed', 'Dry run'], [[
            $result->processed,
            $result->succeeded,
            $result->failed,
            $dryRun ? 'yes' : 'no',
        ]]);

        foreach ($result->errors as $error) {
            $io->warning($error);
        }

        return $result->failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
