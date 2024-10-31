<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json(['data' => $invoices], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = auth()->user();
        $invoice_data = $request->all();
        $invoice_data['user_id']  = $user->id;
        $invoice = Invoice::create($invoice_data);
        foreach ($request->products as $product) {
            $product['invoice_id'] = $invoice->id;
            Product::create($product);
        }


        return response()->json(['message' => 'تم حفظ الفاتورة بنجاح.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function download(Request $request)
    {
        $data = Invoice::find($request->input('id'));
        $data->load('products');
        $user = auth()->user();
        $user->load('media');
        $logo = $user->getMedia()->last();
        $logoBase64 = base64_encode(file_get_contents($logo->getPath())); // Path method provides local path
        $logoSrc = 'data:image/png;base64,' . $logoBase64;
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A3',  // Based on your requirement
            'fontDir' => array_merge($fontDirs, [
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'Cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'I' => 'Cairo-Bold.ttf',
                ]
            ],
            'default_font' => 'Cairo'  // Or 'Amiri' if you prefer

        ]);
        $mpdf->autoArabic = true;

        $mpdf->SetDirectionality('rtl'); // Ensures text flows right-to-left
        $html = view('invoice', ['data' => $data, 'logo' => $logoSrc])->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('invoice.pdf', 'D');  // 'I' for inline viewing; use 'D' to force download

    }
}
