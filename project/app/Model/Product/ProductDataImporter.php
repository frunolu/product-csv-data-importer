<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\OptimisticLockException;
use ParseCsv\csv;
use App\Model\Product\ProductRepository;
use Tracy\Debugger;
use ZipArchive;


class ProductDataImporter
{


    /**
     * @var csv $csvParser @inject
     */
    private csv $csvParser;

    /**
     * @var ProductRepository $productRepository @inject
     */
    private ProductRepository $productRepository;

    /**
     * @var EntityManager $entityManager @inject
     */
    private EntityManager $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager, csv $csv)
    {
        $this->csvParser = $csv;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }
//    /**
//     * @param csv $csvParser
//     * @param ProductRepository $productRepository
//     * @param EntityManager $entityManager
//     */
//    public function __construct(csv $csvParser, ProductRepository $productRepository, EntityManager $entityManager)
//    {
//        $this->csvParser = $csvParser;
//        $this->productRepository = $productRepository;
//        $this->entityManager = $entityManager;
//    }
    private function csvParser()
    {
        $this->csvParser->auto('project/app/Data/Zadanie-jr-PHP.zip');
    }
//
//    public function prepare(string $zippedFile): array
//    {
//        /**
//         * odzipujes subor
//         * vyparsovat csv
//         */
//
//        /**
//         * destination: The $destination parameter can be used to specify the location where to extract the files.
//         * entries: The $entries parameter can be used to specify a single file name which is to be extracted, or you can use it to pass an array of files.
//         */

//  ZipArchive::extractTo( string $destination, mixed $entries ) : bool


//        $zip = new ZipArchive;
//
//// Zip File Name
//        if ($zip->open('project/app/Data/Zadanie - jr PHP.zip') === true) {
//            // Unzip Path
//            $zip->extractTo('project/app/Data');
//            $zip->close();
//            echo 'Unzipped Process Successful!';
//        } else {
//            echo 'Unzipped Process failed';
//        }
//    }


    public function prepare(): object|array
//    {
//
//        $this->csvParser();
//        $csvPath = "/project/app/Data/commonData.csv";
    {
        echo "<html><body><center><table>\n\n";


        // Open a file
        $file = fopen("/project/app/Data/stockData.csv", "rb");
        $data = fgetcsv($file, 1000, ";");
        // Fetching data from csv file row by row
//        while (($data = fgetcsv($file)) !== false) {
//            // HTML tag for placing in row format
//            echo "<tr>";
//            foreach ($data as $i) {
//                echo "<td>".htmlspecialchars($i)
//                    ."</td>";
//            }
//            echo "</tr> \n";
//        }

        // Closing the file
        fclose($file);
        Debugger::barDump($data);

        return $data;


        echo "\n</table></center></body></html>";
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function import(string $file): void
    {
        $data = $this->prepare($file);
        foreach ($data as $productDataRow) {
            // import do db

            $product = $this->productRepository->findBySku($productDataRow['sku']);
            if ($existingProduct = $this->productRepository->findBySku($productDataRow['sku'])) {
                //update
                $this->updateProduct();

                $existingProduct = $product->getStock();
                $product->setStock($productDataRow['stock'] + $existingProduct);
                continue;
            } else {
                //import {
                $this->createProduct();
                try {
                    $this->entityManager->persist($product);
                } catch (ORMException $e) {
                }
            }
        }

        $this->entityManager->flush();
    }

    private function updateProduct($product, $productDataRow): void
    {
        $existingProduct = $product->getStock();
        $product->setStock($productDataRow['stock'] + $existingProduct);
        try {
            $this->entityManager->persist($product);
        } catch (ORMException $e) {
        }
    }

    /**
     * @throws ORMException
     */
    private function createProduct($productDataRow)
    {

    }


}
