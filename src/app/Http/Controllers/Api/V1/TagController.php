<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/tags",
     * summary="index",
     * description="возвращаем список тегов",
     * operationId="index",
     *
     * @OA\Response(
     *    response=200,
     *    description="Список тегов",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * @return Tag[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Tag::all();
    }

    /**
     * @OA\Post(
     * path="/api/tags",
     * summary="store",
     * description="создаем тег",
     * operationId="store",
     * @OA\RequestBody(
     *    required=true,
     *    description="введите тег",
     *    @OA\JsonContent(
     *       required={"name"},
     *    @OA\Property( property="name", type="string", example="Tag number X" ),
     *   ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="созданный тег",
     *    @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     * )
     * )
     */
    /**
     * @param TagRequest $request
     * @return mixed
     */
    public function store(TagRequest $request)
    {
        return Tag::create($request->validated());
    }

    /**
     * @OA\Get(
     * path="/api/tags/{id}",
     * summary="show",
     * description="возвращаем тег",
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
     *    description="тег по id",
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
     * @param int $tag_id
     * @return string
     */
    public function show(int $tag_id)
    {
        try {
            return Tag::findOrFail($tag_id);
       }
        catch (ModelNotFoundException $exception)
        {
            return response()->json(['tag' => 'Not Found!'], 404);
        }

    }


    /**
     * @OA\Put(
     * path="/api/tags/{id}",
     * summary="update",
     * description="редактируем тег",
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
     *    description="введите новое значение тега",
     *    @OA\JsonContent(
     *       required={"id", "name"},
     *    @OA\Property( property="name", type="string", example="Tag number 101" ),
     *    @OA\Property( property="id", type="integer", example=1 ),
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
     * @param TagRequest $request
     * @return mixed
     */
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int $tag_id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, $tag_id)
    {

        try {
            $tag = Tag::findOrFail($tag_id);
            $tag->fill($request->except(['id']));
            logger("update tag: {$tag->name}");
            $tag->save();
            return response()->json($tag);
        }
        catch (\Throwable $exception)
        {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }


  /*  public function destroy(TagRequest $request, int $id)
    {
        $tag = Tag::findOrFail($id);
        if ($tag->forceDelete()) {
            logger("force delete tag: {$tag->name}");
            return response(null, 204);
        }

    }*/
}
