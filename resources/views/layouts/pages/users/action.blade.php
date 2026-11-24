<div class="users-actions">
  <a href="{{ route('users.show', $user) }}" class="users-action-btn" title="Detail">
    <i class="bi bi-eye"></i>
  </a>
  <a href="{{ route('users.edit', $user) }}" class="users-action-btn" title="Edit">
    <i class="bi bi-pencil"></i>
  </a>
  @if (Auth::id() !== $user->id)
    <form
      action="{{ route('users.destroy', $user) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Yakin ingin menghapus user {{ e($user->name) }}?')"
    >
      @csrf
      @method('DELETE')
      <button type="submit" class="users-action-btn text-danger border-0 bg-transparent" title="Hapus">
        <i class="bi bi-trash"></i>
      </button>
    </form>
  @endif
</div>
