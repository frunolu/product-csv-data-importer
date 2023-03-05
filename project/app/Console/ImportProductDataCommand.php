<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Product\Product;
use App\Model\Product\ProductDataImporter;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tracy\Debugger;


final class ImportProductDataCommand extends Command
{
//    private const INPUT_ARG = 'file';

private SymfonyStyle $io;
    private ProductDataImporter $productDataImporter;
    private $entityManager;
    private $productRepository;

    public function __construct(
        ProductDataImporter $productDataImporter,

    ) {
        parent::__construct();
        $this->productDataImporter = $productDataImporter;
    }

    protected function configure(): void
    {
        $this
            ->setName('product:import')
            ->setDescription('Import product data from zipped csv file')
            ->addArgument('file', InputArgument::REQUIRED, 'Csv Product Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    $file = $input->getArgument('file');

//        $this->productDataImporter->import($input->getArgument('file'));

        $data = $this->prepare($file);
        foreach ($data as $productDataRow) {
//            // import do db


            if ($existingProduct = $this->productRepository->findBySku(['sku'=> $productDataRow['sku']])) {
                //update
                $existingProduct->getStock();
                $existingProduct->setStock($productDataRow['stock'] + $existingProduct);
                continue;
            }

//import


            $product = new Product();
            $product->setSku($productDataRow['sku']);
            $product->setEan($productDataRow['ean']);
            $product->setName($productDataRow['name']);
            $product->setShortDesc($productDataRow['shortDesc']);
            $product->setManufacturer($productDataRow['manufacturer']);
            $product->setPrice($productDataRow['price']);
            $product->setStock($productDataRow['stock']);
            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();




        $output->writeln('Importing product data from ' . $file);
        $output->writeln('Product data has been imported.');
        $io = new SymfonyStyle($input, $output);
        $io->success('Importing product data from ' . $file);
    return Command::SUCCESS;


    }

    //nefunguje
//    protected function configure(): void
//    {
//        $this
//            ->setName('product:import')
//            ->setDescription('Import product data from zipped csv file')
//            ->addArgument(self::INPUT_ARG, InputArgument::REQUIRED, 'Zipped Product Data', 'data/sourceData.zip');
//  }

//    protected function execute(InputInterface $input, OutputInterface $output )
//    {
//        $filePath = '/path/to/file.csv';
//
// Create a new instance of the League\Csv\Reader class
//        $csv = Reader::createFromPath($filePath);
//
// Set the header offset to 0 so that the first row is treated as headers
//        $csv->setHeaderOffset(0);
//
//        $username = $input->getArgument('username');
//
//        $output->writeln(\sprintf('Adding user %s…', $username));
//      $this->productDataImporter->import($input->getArgument('file'));
//        $name = $input->getArgument('name');
//
//        $filePath = $input->getArgument('file');
//
//        $symfonyStyle = new SymfonyStyle($input, $output);
//        $symfonyStyle->writeln('Importing product data from ' . $filePath);
//        $this->productDataImporter->import($filePath);
//        $symfonyStyle->success('Product data has been imported.');
    private function prepare(): bool|array
    {
        return array(
            [
                'sku'=>'123',
                'ean'=>'123',
                'name'=>'123',
                'shortDesc'=>'123',
                'manufacturer'=>'123',
                'price'=>'123',
                'stock'=>'123',
            ]
        );

//        // Open a file
//        $file = fopen("/project/app/Data/stockData.csv", "rb");
//        $data = fgetcsv($file, 1000, ";");
//        // Fetching data from csv file row by row
////        while (($data = fgetcsv($file)) !== false) {
////            // HTML tag for placing in row format
////            echo "<tr>";
////            foreach ($data as $i) {
////                echo "<td>".htmlspecialchars($i)
////                    ."</td>";
////            }
////            echo "</tr> \n";
////        }
//
//        // Closing the file
//        fclose($file);
//        Debugger::barDump($data);
//
//        return $data;
//
//
//        echo "\n</table></center></body></html>";


    }

    /**
That’s it! You can now run the command:

$ php bin/console product:import data/sourceData.zip

The output should look like this:

Importing product data from data/sourceData.zip
Product data has been imported.

You can also run the command without any arguments:

$ php bin/console product:import

The output should look like this:

Importing product data from data/sourceData.zip
Product data has been imported.

Conclusion

In this article, we have learned how to create a command in Symfony. We have created a command that imports product data from a zip file. The command has one argument that specifies the location of the zip file. You can run the command without arguments and it will use the default value. You can also specify the argument when running the command. We have also learned how to test commands in Symfony.

If you want to learn more about Symfony, check out my Symfony 5 Course.

Symfony 5 Course

Share this article

I
 * */
}
