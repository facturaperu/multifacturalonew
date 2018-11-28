<?php
namespace App\Http\Controllers\Tenant;

use App\Core\WS\Signed\Certificate\X509Certificate;
use App\Core\WS\Signed\Certificate\X509ContentType;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use Exception;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function record()
    {
        $company = Company::first();

        return [
            'certificate' => $company->certificate
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $company = Company::first();
                $password = $request->input('password');
                $file = $request->file('file');
                $pfx = file_get_contents($file);
                $certificate = new X509Certificate($pfx, $password);
                $pem = $certificate->export(X509ContentType::PEM);
                $name = 'certificate_'.$company->number.'.pem';
                if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'certificates'))) {
                    mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'certificates'));
                }
                file_put_contents(storage_path('app'.DIRECTORY_SEPARATOR.'certificates'.DIRECTORY_SEPARATOR.$name), $pem);
                $company->certificate = $name;
                $company->save();

                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    public function destroy()
    {
        $company = Company::first();
        $company->certificate = null;
        $company->save();

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }
}