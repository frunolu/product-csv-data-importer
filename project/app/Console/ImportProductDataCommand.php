<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Product\ProductDataImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ImportProductDataCommand extends Command
{
    private const INPUT_ARG = 'file';

    private SymfonyStyle $symfonyStyle;

    private ProductDataImporter $productDataImporter;

    public function __construct(
        ProductDataImporter $productDataImporter,
        SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
        $this->symfonyStyle = $symfonyStyle;
        $this->productDataImporter = $productDataImporter;
    }

    protected function configure(): void
    {
        $this
            ->setName('import:product')
            ->setDescription('Import product data from zipped csv file')
            ->addArgument(self::INPUT_ARG, InputArgument::REQUIRED, 'Zipped Product Data', 'data/sourceData.zip');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->productDataImporter->import($input->getArgument(self::INPUT_ARG));

    }
}
