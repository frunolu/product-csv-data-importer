<?php

declare(strict_types=1);

namespace App\Model\Product;

use Doctrine\ORM\EntityManager;
use ParseCsv\csv;
use ZipArchive;


class ProductDataImporter
{

    /**
     * @var csv $csvParser @inject
     */
    private csv  $csvParser;

    private ProductRepository $productRepository;

    private EntityManager $entityManager;

    /**
     * @param csv $csvParser
     * @param ProductRepository $productRepository
     * @param EntityManager $entityManager
     */
    public function __construct(csv $csvParser, ProductRepository $productRepository, EntityManager $entityManager)
    {
        $this->csvParser = $csvParser;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }
    private function csvParser()
    {
        $this->csvParser->auto('project/app/Data/Zadanie - jr PHP.zip');
    }

    private function prepare(string $zippedFile): array
    {
        /**
         * odzipujes subor
         * vyparsovat csv
         */

        /**
         * destination: The $destination parameter can be used to specify the location where to extract the files.
        entries: The $entries parameter can be used to specify a single file name which is to be extracted, or you can use it to pass an array of files.
         */

//  ZipArchive::extractTo( string $destination, mixed $entries ) : bool


        $zip = new ZipArchive;

// Zip File Name
        if ($zip->open('project/app/Data/Zadanie - jr PHP.zip') === TRUE) {

            // Unzip Path
            $zip->extractTo('project/app/Data');
            $zip->close();
            echo 'Unzipped Process Successful!';
        } else {
            echo 'Unzipped Process failed';
        }


        return  $this->csvParser->auto($zip);

//        $data = [
////            ['sku' => '564584', 'ean' => 'asdasd'],
////            ['sku' => '564584', 'ean' => 'asdasd'],
////            ['sku' => '564584', 'ean' => 'asdasd'],
//        ];
    }

    public function import(string $zippedFile): void
    {
        $data = $this->prepare($zippedFile);
        foreach ($data as $productDataRow) {
            // import do db

            $product = $this->productRepository->finnBySku($productDataRow['sku']);
            if($product === null){
                //import {
                $product = new Product();
                $product->setSku($data[$productDataRow['sku']] ?? null);
                $product->setEan($data[$productDataRow['ean']] ?? null);
                $product->setName($data[$productDataRow['name']] ?? null);
                $product->setShortDesc($data[$productDataRow['shortDesc']] ?? null);
                $product->setManufacturer($data[$productDataRow['manufacturer']] ?? null);
                $product->setPrice($data[$productDataRow['price']] ?? null);
                $this->entityManager->persist($product);

            } else {
                //update
                $currentStock = $product->getStock();
                $product->setStock($productDataRow['stock'] + $currentStock);
            }

        }

        $this->entityManager->flush();

    }

}
