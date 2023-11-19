<?php 

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if($acceptHeader == 'application/json' || $acceptHeader == 'application/xml'){
            $ulasan = Ulasan::OrderBy("id", "DESC")->paginate(2);

            if($acceptHeader == 'application/json' || $acceptHeader == 'application/xml'){
                return response()->json($ulasan->items('data'), 200);
            }else{
                $xml = new \SimpleXMLElement('<ulasan/>');
                foreach ($ulasan->items('data')as $item){
                    $xmlItem = $xml->addChild('ulasan');

                    $xmlItem->addChild('id',$item->id);
                    $xmlItem->addChild('judul',$item->judul);
                    $xmlItem->addChild('username',$item->username);
                    $xmlItem->addChild('rating',$item->rating);
                    $xmlItem->addChild('komentar',$item->komentar);
                    $xmlItem->addChild('created_at',$item->created_at);
                    $xmlItem->addChild('updated_at',$item->updated_at);
                }
                return $xml->asXML();
            }
        }else{
            return response('not Accepted', 416);
        }
    }

    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $ulasan = Ulasan::find($id);

                if (!$ulasan) {
                    abort(404);
                }

                return response()->json($ulasan, 200);
            } else {
                return response('Tipe Media Tidak Mendukung!', 415);
            }
        } else {
            return response('Tidak Bisa Diterima!', 406);
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $ulasan = Ulasan::find($id);

        if (!$ulasan) {
            abort(404);
        }

        $validationRules = [
            'judul' => 'required',
            'username' => 'required',
            'rating' => 'required',
            'komentar' => 'required',
        ];

        $validator = Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ulasan->fill($input);
        $ulasan->save();

        return response()->json($ulasan, 200);
    }

    public function store(Request $request){
        $input = $request->all();
        $ulasan = Ulasan::create($input);

        return response()->json($ulasan, 200);
    }


    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || 'application/xml') {
                $ulasan = Ulasan::find($id);

                if (!$ulasan) {
                    abort(404);
                }

                $ulasan->delete();
                $message = ['message' => 'delete data berhasil'];

                return response()->json($message, 200);
            } else {
                return response('Tipe Media Tidak Mendukung!', 415);
            }
        } else {
            return response('Tidak Bisa Diterima!', 406);
        }
    }

}