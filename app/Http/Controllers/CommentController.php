<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    //
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function comment(Request $request)
    {
        $data = $request->all();

        $comment = new Comments();

        $comment->customer_id = Session::get('customer_id');
        $comment->product_id = $data['product_id'];
        $comment->review_text = $data['comment'];
        $comment->color = $data['color'];
        $comment->size = $data['size'];
        $comment->status = 'pending';
        $comment->created_at = now();
        if (!Session::get('customer_id')) {
            Session::put('error', 'Khách hàng chưa đăng nhập');
            return Redirect::to('/home/account/login');
        }
        $comment->save();

        return redirect()->back()->with('message', 'Cảm ơn bạn đã bình luận!');
    }

    public function list_comment()
    {
        $this->AuthLogin();
        $comment = DB::table('tbl_review')
            ->join('tbl_product_variants', 'tbl_product_variants.variants_id', '=', 'tbl_review.product_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_review.customer_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->select(
                'tbl_review.*',
                'tbl_product.product_name',
                'tbl_customer.customer_name'
            )
            ->orderBy('tbl_review.created_at', 'desc')
            ->get();
        return view('admin.comment.list_comment')
            ->with('comment', $comment);
    }

    public function update($comment_id)
    {
        $this->AuthLogin();
        $comment = Comments::find($comment_id);
        $statusOptions = [
            'pending' => 'Đang chờ xử lý',
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Đã từ chối'
        ];
        $comments = view('admin.comment.update')->with('comment', $comment)->with('statusOptions', $statusOptions);
        return view('admin_layout')->with('admin.comment.update', $comments);
    }

    public function update_comment(Request $request, $comment_id)
    {
        $data = $request->all();

        $comment = Comments::find($comment_id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        Session::put('message', 'Cập nhật trạng thái bình luận thành công !');
        return Redirect::to('/admin/comments');
    }
}
