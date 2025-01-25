<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\File;

class ConvertPdfCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:convert {inputPdf} {outputPdf} {unique_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a PDF to images and regenerate a PDF using those images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inputPdf = $this->argument('inputPdf');
        $outputPdf = $this->argument('outputPdf');
        $unique_id = $this->argument('unique_id');
        $imageDirectory = storage_path('app/pdf_images/'.$unique_id);

        // Ensure the input PDF exists
        if (!file_exists($inputPdf)) {
            echo "The file $inputPdf does not exist.\n";
            $this->error("The file $inputPdf does not exist.");
            return 1;
        }

        // Ensure the output directory exists
        if (!File::isDirectory($imageDirectory)) {
            File::makeDirectory($imageDirectory, 0777, true);
        }

        try {
            // Step 1: Convert PDF to Images
            $images = $this->convertPdfToImages($inputPdf, $imageDirectory);

            // Step 2: Generate a New PDF from Images
            $this->generatePdfFromImages($images, $outputPdf);

            // Clean up: Delete temporary images
            foreach ($images as $image) {
                unlink($image);
            }

            $this->info("PDF successfully converted and saved to $outputPdf.");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function convertPdfToImages($pdfPath, $outputDirectory, $imageFormat = 'png')
    {
        // echo "Converting to PNG\n";
        // Full path to pdftoppm (update this path based on your system)
        // $popplerPath = env('POPPLER_PATH'); // 'C:\poppler\bin\pdftoppm.exe'; // Update this path
        $popplerPath = 'pdftoppm'; // 'C:\poppler\bin\pdftoppm.exe'; // Update this path
        // dd($popplerPath);
        $outputPath = $outputDirectory . '/page';

        // Command to convert PDF to images
        $command = "$popplerPath -$imageFormat $pdfPath $outputPath";
        // echo $command;
        // dd($command);
        // Execute the command
        $output = "";
        $returnVar = "";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error converting PDF to images: " . implode("\n", $output));
        }

        // Collect all generated image files
        return glob($outputDirectory . "/page-*.$imageFormat");
    }

    private function generatePdfFromImages(array $images, $outputFile)
    {
        $mpdf = new Mpdf();

        foreach ($images as $image) {
            $mpdf->AddPage();
            $mpdf->Image($image, 0, 0, 210, 297, 'jpg', '', true, false); // A4 size
        }

        $mpdf->Output($outputFile, \Mpdf\Output\Destination::FILE);
    }
}
