<?php
namespace App\Http\Controllers;

class AddressController extends Controller
{
    //
    public function getTinhTp()
    {
        $json = file_get_contents(public_path('data/tinh_tp.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu tỉnh/thành'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            $result[] = ['code' => $code, 'name' => $item['name']];
        }

        return response()->json($result);
    }

    public function getQuanHuyen($tinh_code)
    {
        $json = file_get_contents(public_path('data/quan_huyen.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu quận/huyện'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            if (isset($item['parent_code']) && $item['parent_code'] === $tinh_code) {
                $result[] = ['code' => $code, 'name' => $item['name']];
            }
        }

        return response()->json($result);
    }

    public function getXaPhuong($quan_code)
    {
        $json = file_get_contents(public_path('data/xa_phuong.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu xã/phường'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            if (isset($item['parent_code']) && $item['parent_code'] === $quan_code) {
                $result[] = ['code' => $code, 'name' => $item['name']];
            }
        }

        return response()->json($result);
    }
    public function TinhTp()
    {
        $json = file_get_contents(public_path('data/tinh_tp.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu tỉnh/thành'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            $result[] = ['code' => $code, 'name' => $item['name']];
        }

        return response()->json($result);
    }

    public function QuanHuyen($tinh_code)
    {
        $json = file_get_contents(public_path('data/quan_huyen.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu quận/huyện'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            if (isset($item['parent_code']) && $item['parent_code'] === $tinh_code) {
                $result[] = ['code' => $code, 'name' => $item['name']];
            }
        }

        return response()->json($result);
    }

    public function XaPhuong($quan_code)
    {
        $json = file_get_contents(public_path('data/xa_phuong.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu xã/phường'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            if (isset($item['parent_code']) && $item['parent_code'] === $quan_code) {
                $result[] = ['code' => $code, 'name' => $item['name']];
            }
        }

        return response()->json($result);
    }

    public function cart_getTinhTp()
    {
        $json = file_get_contents(public_path('data/tinh_tp.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu tỉnh/thành'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            $result[] = ['code' => $code, 'name' => $item['name']];
        }

        return response()->json($result);
    }

    public function cart_getQuanHuyen($tinh_code)
    {
        $json = file_get_contents(public_path('data/quan_huyen.json'));
        $data = json_decode($json, true);

        if (! is_array($data)) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu quận/huyện'], 500);
        }

        $result = [];
        foreach ($data as $code => $item) {
            if (isset($item['parent_code']) && $item['parent_code'] === $tinh_code) {
                $result[] = ['code' => $code, 'name' => $item['name']];
            }
        }

        return response()->json($result);
    }

}
