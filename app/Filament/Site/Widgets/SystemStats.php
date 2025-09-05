<?php

namespace App\Filament\Site\Widgets;

use App\Models\Announcement;
use App\Models\Awareness;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SystemStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // 🔹 Views
        $todayViews = DB::table('post_views')
            ->whereDate('view_date', $today)
            ->sum('views');

        $yesterdayViews = DB::table('post_views')
            ->whereDate('view_date', $today->copy()->subDay())
            ->sum('views');

        $monthViews = DB::table('post_views')
            ->whereBetween('view_date', [$monthStart, now()])
            ->sum('views');

        $totalViews = DB::table('post_views')->sum('views');

        // 🔹 Content Counts
        $totalPosts = Post::count();
        $totalAwareness = Awareness::count();
        $totalAnnouncements = Announcement::count();

        // 🔹 Percentage growth today vs yesterday
        $growth = $yesterdayViews > 0
            ? round((($todayViews - $yesterdayViews) / $yesterdayViews) * 100, 1)
            : ($todayViews > 0 ? 100 : 0);

        return [
            Stat::make('📊 زيارات اليوم', $todayViews)
                ->description(
                    'مقارنة بالأمس: ' .
                    ($growth >= 0 ? '▲ ' . $growth . '%' : '▼ ' . abs($growth) . '%')
                )
                ->color($growth >= 0 ? 'success' : 'danger'),

            Stat::make('🗓️ زيارات هذا الشهر', $monthViews)
                ->description('منذ ' . $monthStart->locale('ar')->translatedFormat('d F'))
                ->color('warning'),

            Stat::make('🌍 إجمالي الزيارات', $totalViews)
                ->description('كل المشاهدات عبر جميع المقالات')
                ->color('info'),

            Stat::make('📝 عدد المقالات', $totalPosts)
                ->description('إجمالي المقالات المنشورة')
                ->color('primary'),

            Stat::make('📢 عدد الإعلانات', $totalAnnouncements)
                ->description('الإعلانات المضافة للنظام')
                ->color('secondary'),

            Stat::make('🎓 عدد التوعيات', $totalAwareness)
                ->description('المحتوى التوعوي المنشور')
                ->color('success'),
        ];
    }
}
