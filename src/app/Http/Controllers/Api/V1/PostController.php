<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/posts",
     * summary="index",
     * description="возвращаем список постов",
     * operationId="index",
     *
     * @OA\Response(
     *    response=200,
     *    description="Список постов",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * @OA\Post(
     * path="/api/posts",
     * summary="store",
     * description="создаем пост",
     * operationId="store",
     * @OA\RequestBody(
     *    required=true,
     *    description="введите пост",
     *    @OA\JsonContent(
     *       required={"title", "tags"},
     *    @OA\Property( property="title", type="string", example="Post number X 55" ),
     *    @OA\Property( property="tags", type="array", collectionFormat="multi",
     *              @OA\Items(
     *                 type="integer",
     *                 example={1, 2},
     *              ) ),
     *   ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="созданный пост",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $list_tags = ((array)$request->only('tags'))['tags'][0];

        DB::transaction(function () use ($request, $list_tags) {

            $post = Post::create($request->validated());


            $post->tags()->attach($list_tags);
            return $post::with('tags');
        });
    }

    /**
     * @OA\Get(
     * path="/api/posts/{id}",
     * summary="show",
     * description="возвращаем пост с тегами",
     * operationId="show",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * @OA\Response(
     *    response=200,
     *    description="пост по id",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *      @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     * )
     * )
     */
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            //return new PostResource(
            return Post::with('tags')->findOrFail($id);
            //);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['post' => 'Not Found!'], 404);
        }
    }

    /**
     * @OA\Put(
     * path="/api/posts/{id}",
     * summary="update",
     * description="редактируем пост",
     * operationId="update",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * @OA\RequestBody(
     *    required=true,
     *    description="введите новое значение поста",
     *    @OA\JsonContent(
     *       required={"id", "title"},
     *    @OA\Property( property="title", type="string", example="Пост number 101" ),
     *    @OA\Property( property="id", type="integer", example=1 ),
     *    @OA\Property( property="tags", type="array", collectionFormat="multi",
     *              @OA\Items(
     *                 type="integer",
     *                 example={1, 2},
     *              ) ),
     *   ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="отредактированный тег",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * @param PostRequest $request
     * @param int $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PostRequest $request, int $post_id)
    {
        try {

            $list_tags = ((array)$request->only('tags'))['tags'][0];
            $post = Post::findOrFail($post_id);
            DB::transaction(function () use ($request, $post, $list_tags) {

                $post->fill($request->except(['id']));
                $post->save();
                $post->tags()->sync($list_tags);
            });
            logger("update post: {$post->title}");
            return Post::with('tags')->findOrFail($post_id);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete (
     * path="/api/posts/{id}",
     * summary="delete",
     * description="удаляем пост",
     * operationId="destroy",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     * ),
     * @OA\Response(
     *     response=204,
     *    description="удаление поста",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);
        if ($post->forceDelete()) {
            logger("force delete post: {$post->title}");
            return response(null, 204);
        }
    }
}
