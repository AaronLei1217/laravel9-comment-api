<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    private $filePath = 'messages.json';

    /**
     * 獲取所有留言
     */
    public function index()
    {
        // 讀取文件內容
        $content = Storage::get($this->filePath);

        // 將文件內容轉換為PHP陣列
        $messages = json_decode($content, true);

        return response()->json($messages, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 創建新的留言
     */
    public function create(Request $request)
    {
        // 讀取文件內容
        $content = Storage::get($this->filePath);

        // 將文件內容轉換為PHP陣列
        $messages = json_decode($content, true);

        if ($messages === null) {
            $messages = [];
        }

        // 從請求中獲取留言內容
        $message = $request->input('message');

        // 將新的留言添加到陣列中
        $messages[] = ['message' => $message];

        // 將更新後的陣列存儲回文件，中文不會被轉成unicode
        Storage::put($this->filePath, json_encode($messages, JSON_UNESCAPED_UNICODE));

        return response()->json(['message' => 'Message has been created']);
    }
}