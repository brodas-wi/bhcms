<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'category_id',
        'content',
        'serialized_content',
        'active_plugins',
        'plugin_data',
        'template_id',
        'status',
        'user_id',
        'thumbnail',
        'date_published',
        'version',
        'navbar_id',
        'footer_id'
    ];

    protected $casts = [
        'date_published' => 'datetime',
        'serialized_content' => 'array',
        'active_plugins' => 'array',
        'plugin_data' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            $page->slug = $page->generateUniqueSlug($page->name);
            $page->version = '1.0.0';
        });

        static::updating(function ($page) {
            if ($page->isDirty('name')) {
                $page->slug = $page->generateUniqueSlug($page->name);
            }
        });

        static::saving(function ($page) {
            if (is_string($page->active_plugins)) {
                $page->active_plugins = json_decode($page->active_plugins, true);
            }
            if (is_string($page->plugin_data)) {
                $page->plugin_data = json_decode($page->plugin_data, true);
            }
        });
    }

    public function generateUniqueSlug($name): string
    {
        $slug = Str::slug($name);
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getCleanContent(): string
    {
        $content = $this->content;
        $content = preg_replace('/<html[^>]*>|<\/html>|<head[^>]*>.*?<\/head>|<body[^>]*>|<\/body>/is', '', $content);
        $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);
        $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);
        return trim($content);
    }

    public function validateVersion(string $version): bool
    {
        return preg_match('/^\d+\.\d+\.\d+$/', $version) === 1;
    }

    public function isVersionGreaterThan(string $version1, string $version2): bool
    {
        $v1Parts = array_map('intval', explode('.', $version1));
        $v2Parts = array_map('intval', explode('.', $version2));

        for ($i = 0; $i < 3; $i++) {
            if ($v1Parts[$i] > $v2Parts[$i])
                return true;
            if ($v1Parts[$i] < $v2Parts[$i])
                return false;
        }
        return false;
    }

    public function getNextMinorVersion(): string
    {
        $parts = explode('.', $this->version);
        $parts[1]++;
        $parts[2] = 0;
        return implode('.', $parts);
    }

    public function getNextMajorVersion(): string
    {
        $parts = explode('.', $this->version);
        $parts[0]++;
        $parts[1] = 0;
        $parts[2] = 0;
        return implode('.', $parts);
    }

    public function getGrapesJsComponent()
    {
        return [
            'type' => $this->name,
            'name' => $this->original_name,
            'category' => 'Plugins',
            'draggable' => true,
            'droppable' => false,
            'icon' => '<i class="fas fa-puzzle-piece"></i>',
            'content' => [
                'type' => $this->name,
                'pluginId' => $this->id
            ]
        ];
    }

    public function renderForEditor()
    {
        try {
            $instance = $this->getInstance();
            if ($instance && method_exists($instance, 'renderForEditor')) {
                return $instance->renderForEditor();
            }
            return $this->renderView('index');
        } catch (\Exception $e) {
            \Log::error("Error rendering plugin for editor: {$e->getMessage()}");
            return "<div class='plugin-error'>Error loading plugin: {$this->original_name}</div>";
        }
    }

    public function getActivePluginInstances()
    {
        $globalPlugins = Plugin::where('is_global', true)
            ->where('is_active', true)
            ->get();

        $pagePlugins = collect([]);
        if ($this->active_plugins) {
            $pagePlugins = Plugin::whereIn('id', $this->active_plugins)
                ->where('is_active', true)
                ->get();
        }

        return $globalPlugins->merge($pagePlugins);
    }

    public function isPluginActive($pluginId)
    {
        if (is_null($this->active_plugins)) {
            return false;
        }
        return in_array($pluginId, $this->active_plugins);
    }

    public function getPluginView($pluginId)
    {
        if (!$this->plugin_data || !isset($this->plugin_data[$pluginId])) {
            return 'index';
        }
        return $this->plugin_data[$pluginId]['view'] ?? 'index';
    }

    public function updatePluginData($pluginId, $viewName, $additionalData = [])
    {
        $pluginData = $this->plugin_data ?? [];
        $pluginData[$pluginId] = array_merge([
            'view' => $viewName
        ], $additionalData);

        $this->plugin_data = $pluginData;
        return $this->save();
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function navbar(): BelongsTo
    {
        return $this->belongsTo(Navbar::class);
    }

    public function footer(): BelongsTo
    {
        return $this->belongsTo(Footer::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class)->orderBy('created_at', 'desc');
    }
}
