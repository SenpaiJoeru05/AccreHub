<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Document;
use App\Models\Parameter;
use App\Models\Section;
use App\Models\Subfolder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ActivityLog;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class AreaDocuments extends Component
{
    use WithPagination, WithFileUploads;

    public $area_id;
    public $area;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $year = '';

    #[Url(history: true)]
    public $parameter_id = null;

    #[Url(history: true)]
    public $section_id = null;

    #[Url(history: true)]
    public $subfolder_ids = [];

    public $years = [];

    // Form fields for creating folders
    public $folderName = '';
    public $folderSlug = '';
    public $folderDescription = '';

    // Form fields for editing folders
    public $editingFolderId = null;
    public $editingFolderType = null;
    public $editFolderName = '';
    public $editFolderSlug = '';
    public $editFolderDescription = '';

    // Form fields for documents
    public $documentTitle = '';
    public $documentFile = null;
    public $documentYear = '';
    public $documentDescription = '';

    // Form fields for editing documents
    public $editingDocumentId = null;
    public $editDocumentTitle = '';
    public $editDocumentYear = '';
    public $editDocumentDescription = '';

    // Document editing methods
    public function startEditingDocument($documentId)
    {
        $document = Document::find($documentId);
        $this->editingDocumentId = $documentId;
        $this->editDocumentTitle = $document->title;
        $this->editDocumentYear = $document->year;
        $this->editDocumentDescription = $document->description;
    }

    public function cancelEditingDocument()
    {
        $this->editingDocumentId = null;
        $this->editDocumentTitle = '';
        $this->editDocumentYear = '';
        $this->editDocumentDescription = '';
    }

    public function updateDocument()
    {
        $this->validate([
            'editDocumentTitle' => 'required|string|max:255',
            'editDocumentYear' => 'required|integer|min:1900|max:' . date('Y'),
            'editDocumentDescription' => 'nullable|string',
        ]);

        $document = Document::find($this->editingDocumentId);
        $user = auth()->user();
        $area = $this->area->name ?? 'Unknown Area';

        $document->update([
            'title' => $this->editDocumentTitle,
            'year' => $this->editDocumentYear,
            'description' => $this->editDocumentDescription,
        ]);

        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => 'updated_document',
            'description' => $user?->name . ' updated document "' . $document->title . '" in area "' . $area . '"',
        ]);

        $this->cancelEditingDocument();
        $this->dispatch('document-updated');
        $this->dispatch('refreshPreviews');
    }

    // Folder editing methods
    public function startEditing($folderId, $folderType)
    {
        $this->editingFolderId = $folderId;
        $this->editingFolderType = $folderType;

        switch ($folderType) {
            case 'parameter':
                $folder = Parameter::find($folderId);
                break;
            case 'section':
                $folder = Section::find($folderId);
                break;
            case 'subfolder':
                $folder = Subfolder::find($folderId);
                break;
        }

        $this->editFolderName = $folder->name;
        $this->editFolderSlug = $folder->slug;
        $this->editFolderDescription = $folder->description;
    }

    public function cancelEditing()
    {
        $this->editingFolderId = null;
        $this->editingFolderType = null;
        $this->editFolderName = '';
        $this->editFolderSlug = '';
        $this->editFolderDescription = '';
    }

    public function updateFolder()
    {
        $this->validate([
            'editFolderName' => 'required|string|max:255',
            'editFolderSlug' => 'nullable|string|max:255',
            'editFolderDescription' => 'nullable|string',
        ]);

        $user = auth()->user();
        $area = $this->area->name ?? 'Unknown Area';

        switch ($this->editingFolderType) {
            case 'parameter':
                $folder = Parameter::find($this->editingFolderId);
                $folder->update([
                    'name' => $this->editFolderName,
                    'slug' => $this->editFolderSlug,
                    'description' => $this->editFolderDescription,
                ]);
                $logMessage = ' updated parameter "' . $folder->name . '" in area "' . $area . '"';
                break;
            case 'section':
                $folder = Section::find($this->editingFolderId);
                $folder->update([
                    'name' => $this->editFolderName,
                    'slug' => $this->editFolderSlug,
                    'description' => $this->editFolderDescription,
                ]);
                $logMessage = ' updated section "' . $folder->name . '" in parameter "' . 
                    Parameter::find($this->parameter_id)?->name . '" in area "' . $area . '"';
                break;
            case 'subfolder':
                $folder = Subfolder::find($this->editingFolderId);
                $folder->update([
                    'name' => $this->editFolderName,
                    'slug' => $this->editFolderSlug,
                    'description' => $this->editFolderDescription,
                ]);
                $logMessage = ' updated subfolder "' . $folder->name . '" in section "' . 
                    Section::find($this->section_id)?->name . '" in area "' . $area . '"';
                break;
        }

        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => 'updated_' . $this->editingFolderType,
            'description' => $user?->name . $logMessage,
        ]);

        $this->cancelEditing();
        $this->dispatch('folder-updated');
        $this->dispatch('refreshPreviews');
    }

    public function mount($area): void
    {
        $this->area = Area::findOrFail($area);
        $this->area_id = $this->area->id;

        $this->years = Document::where('area_id', $this->area_id)
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values()
            ->toArray();
    }

    public function updating($name, $value)
    {
        $this->resetPage();
        // Only refresh for specific properties
        if (in_array($name, ['search', 'year', 'parameter_id', 'section_id', 'subfolder_ids'])) {
            $this->dispatch('refreshPreviews');
        }
    }

    public function updatedSearch()
    {
        $this->dispatch('search-updated');
    }

    public function updatedYear()
    {
        $this->dispatch('refreshPreviews');
    }

    public function updatedParameterId()
    {
        $this->section_id = null;
        $this->subfolder_ids = [];
        $this->dispatch('refreshPreviews');
    }

    public function updatedSectionId()
    {
        $this->subfolder_ids = [];
        $this->dispatch('refreshPreviews');
    }

    public function createFolder()
    {
        $this->validate([
            'folderName' => 'required|string|max:255',
            'folderSlug' => 'nullable|string|max:255',
            'folderDescription' => 'nullable|string',
        ]);

        $user = auth()->user();
        $area = $this->area->name ?? 'Unknown Area';

        if (!$this->parameter_id) {
            // Create Parameter
            $parameter = Parameter::create([
                'area_id' => $this->area_id,
                'name' => $this->folderName,
                'slug' => $this->folderSlug,
                'description' => $this->folderDescription,
            ]);
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'added_parameter',
                'description' => $user?->name . ' added parameter "' . $parameter->name . '" to area "' . $area . '"',
            ]);
        } elseif ($this->parameter_id && !$this->section_id) {
            // Create Section
            $section = Section::create([
                'parameter_id' => $this->parameter_id,
                'name' => $this->folderName,
                'slug' => $this->folderSlug,
                'description' => $this->folderDescription,
            ]);
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'added_section',
                'description' => $user?->name . ' added section "' . $section->name . '" to parameter "' . Parameter::find($this->parameter_id)?->name . '" in area "' . $area . '"',
            ]);
        } else {
            // Create Subfolder
            $subfolder = Subfolder::create([
                'section_id' => $this->section_id,
                'parent_id' => end($this->subfolder_ids) ?: null,
                'name' => $this->folderName,
                'slug' => $this->folderSlug,
                'description' => $this->folderDescription,
            ]);
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => 'added_subfolder',
                'description' => $user?->name . ' added subfolder "' . $subfolder->name . '" to section "' . Section::find($this->section_id)?->name . '" in area "' . $area . '"',
            ]);
        }

        $this->folderName = '';
        $this->folderSlug = '';
        $this->folderDescription = '';
        $this->dispatch('folder-created');
        $this->dispatch('refreshPreviews');
    }
    public function confirmDeleteDocument()
    {
        if ($this->editingDocumentId) {
            Document::find($this->editingDocumentId)?->delete(); // Soft delete (archive)
            $this->cancelEditingDocument();
            $this->dispatch('document-updated');
            $this->dispatch('refreshPreviews');
        }
    }

    public function confirmDeleteFolder()
    {
        if ($this->editingFolderId && $this->editingFolderType) {
            switch ($this->editingFolderType) {
                case 'parameter':
                    Parameter::find($this->editingFolderId)?->delete();
                    break;
                case 'section':
                    Section::find($this->editingFolderId)?->delete();
                    break;
                case 'subfolder':
                    Subfolder::find($this->editingFolderId)?->delete();
                    break;
            }
            $this->cancelEditing();
            $this->dispatch('folder-updated');
            $this->dispatch('refreshPreviews');
        }
    }
    public function uploadDocument()
    {
        $this->validate([
            'documentTitle' => 'required|string|max:255',
            'documentFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'documentYear' => 'required|integer|min:1900|max:' . date('Y'),
            'documentDescription' => 'nullable|string',
        ]);

        $path = $this->documentFile->store('documents', 'public');

        $document = Document::create([
            'title' => $this->documentTitle,
            'path' => $path,
            'area_id' => $this->area_id,
            'parameter_id' => $this->parameter_id,
            'section_id' => $this->section_id,
            'subfolder_id' => end($this->subfolder_ids) ?: null,
            'year' => $this->documentYear,
            'description' => $this->documentDescription,
            'uploaded_by' => auth()->id(),
        ]);

        // --- ACTIVITY LOG ---
        $user = auth()->user();
        $area = $this->area->name ?? 'Unknown Area';
        $parameter = $this->parameter_id ? Parameter::find($this->parameter_id)?->name : null;
        $section = $this->section_id ? Section::find($this->section_id)?->name : null;
        $subfolder = !empty($this->subfolder_ids) ? Subfolder::find(end($this->subfolder_ids))?->name : null;

        $pathParts = array_filter([$area, $parameter, $section, $subfolder]);
        $fullPath = implode(' > ', $pathParts);
        $docTitle = $this->documentTitle;

        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => 'uploaded_document',
            'description' => $user?->name . ' uploaded document "' . $docTitle . '" to ' . $fullPath,
        ]);
        // --- END ACTIVITY LOG ---

        // --- DETAILED NOTIFICATION BLOCK WITH CLICKABLE ACTION ---
        $admins = User::where('role', 'admin')->get();

        $url = route('filament.admin.pages.area-documents', [
            'area' => $this->area_id,
            'parameter_id' => $this->parameter_id,
            'section_id' => $this->section_id,
            'subfolder_ids' => $this->subfolder_ids,
        ]);

        Notification::make()
        ->title('New Document Uploaded')
        ->body("{$user->name} uploaded \"{$docTitle}\" to {$fullPath}.")
        ->icon('heroicon-o-document')
        ->color('info')
        ->actions([
            Action::make('view')
                ->label('View Location')
                ->url($url)
                ->openUrlInNewTab(),
            Action::make('markAsRead')
                ->label('Mark as read')
                ->markAsRead(),
        ])
        ->sendToDatabase($admins);
        // --- END NOTIFICATION BLOCK ---

        $this->documentTitle = '';
        $this->documentFile = null;
        $this->documentYear = '';
        $this->documentDescription = '';

        $this->resetPage(); // <-- Add this line

        $this->dispatch('refreshPreviews');
        $this->dispatch('document-uploaded');
    }

    public function render()
    {
        // Get folders based on navigation state
        $folders = collect();
        if (!$this->parameter_id) {
            // Show Parameters
            $folders = Parameter::where('area_id', $this->area_id)->get();
        } elseif ($this->parameter_id && !$this->section_id) {
            // Show Sections
            $folders = Section::where('parameter_id', $this->parameter_id)->get();
        } elseif ($this->section_id) {
            // Show Subfolders
            $parent_id = end($this->subfolder_ids) ?: null;
            $folders = Subfolder::where('section_id', $this->section_id)
                ->where('parent_id', $parent_id)
                ->get();
        }

        // Get documents based on navigation state
        $query = Document::query()
            ->where('area_id', $this->area_id)
            ->where('parameter_id', $this->parameter_id ?: null)
            ->where('section_id', $this->section_id ?: null)
            ->where('subfolder_id', end($this->subfolder_ids) ?: null);

        if ($this->search) {
            $searchTerm = strtolower($this->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereHas('parameter', function ($q) use ($searchTerm) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                    })
                    ->orWhereHas('section', function ($q) use ($searchTerm) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                    })
                    ->orWhereHas('subfolder', function ($q) use ($searchTerm) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                    });
            });
        }

        if ($this->year) {
            $query->where('year', $this->year);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(12);

        // Build breadcrumbs
        $breadcrumbs = [['name' => $this->area->name, 'id' => null]];
        if ($this->parameter_id) {
            $parameter = Parameter::find($this->parameter_id);
            $breadcrumbs[] = ['name' => $parameter->name, 'id' => $parameter->id, 'type' => 'parameter'];
        }
        if ($this->section_id) {
            $section = Section::find($this->section_id);
            $breadcrumbs[] = ['name' => $section->name, 'id' => $section->id, 'type' => 'section'];
        }
        foreach ($this->subfolder_ids as $subfolder_id) {
            $subfolder = Subfolder::find($subfolder_id);
            $breadcrumbs[] = ['name' => $subfolder->name, 'id' => $subfolder->id, 'type' => 'subfolder'];
        }

        return view('livewire.area-documents', [
            'folders' => $folders,
            'documents' => $documents,
            'years' => $this->years,
            'area' => $this->area,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}