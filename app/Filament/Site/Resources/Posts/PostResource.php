<?php

namespace App\Filament\Site\Resources\Posts;

use App\Filament\Site\Resources\Posts\Pages\CreatePost;
use App\Filament\Site\Resources\Posts\Pages\EditPost;
use App\Filament\Site\Resources\Posts\Pages\ListPosts;
use App\Filament\Site\Resources\Posts\Schemas\PostForm;
use App\Filament\Site\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    
    protected static string|null|BackedEnum $navigationIcon = 'gmdi-post-add';

    protected static ?string $label = 'منشور';
    protected static ?string $pluralLabel = 'المنشورات';


    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
