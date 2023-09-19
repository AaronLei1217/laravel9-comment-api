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
        // 讀取JSON文件內容
        $content = Storage::get($this->filePath);
        // 將JSON文件內容轉換為PHP陣列
        $comment = json_decode($content, true);
        // 返回留言部分
        return response()->json($comment['messages'], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 創建新的留言
     */
    public function create(Request $request)
    {
        // 讀取JSON文件內容
        $content = Storage::get($this->filePath);
        // 將JSON文件內容轉換為PHP陣列
        $comment = json_decode($content, true);

        // 從陣列中獲取下一個可用的ID
        $id = $comment['next_id'];
        // 從請求中獲取留言內容
        $message = $request->input('message');

        // 將新留言添加到留言陣列中
        $comment['messages'][] = ['id' => $id, 'message' => $message];
        // 更新下一個可用的ID
        $comment['next_id'] = $id + 1;

        // 將更新後的陣列寫回JSON文件
        Storage::put($this->filePath, json_encode($comment, JSON_UNESCAPED_UNICODE));

        // 返回成功訊息和留言的ID
        return response()->json(['message' => 'Message has been created', 'id' => $id]);
    }

    /**
     * 刪除指定ID的留言
     */
    public function delete($id)
    {
        // 讀取JSON文件內容
        $content = Storage::get($this->filePath);
        // 將JSON文件內容轉換為PHP陣列
        $comment = json_decode($content, true);

        // 遍歷所有留言以找到匹配的ID
        foreach ($comment['messages'] as $index => $msg) {
            if ($msg['id'] == $id) {
                // 如果找到，從陣列中刪除該留言
                unset($comment['messages'][$index]);
                // 重新索引陣列
                $comment['messages'] = array_values($comment['messages']);
                // 將更新後的陣列寫回JSON文件
                Storage::put($this->filePath, json_encode($comment, JSON_UNESCAPED_UNICODE));
                // 返回成功刪除的訊息
                return response()->json(['message' => 'Message has been deleted']);
            }
        }

        // 如果未找到匹配的ID，返回404錯誤
        return response()->json(['message' => 'Message not found'], 404);
    }
}