<?php
use CodeIgniter\Pager\PagerRenderer;
/** @var PagerRenderer $pager */
?>
<nav aria-label="Page navigation">
	<ul class="pagination" >
		<?php if ($pager->hasPreviousPage()): ?>
			<li>
				<a href="<?= $pager->getPreviousPage() ?>" aria- label="Précédent">&laquo; Précédent</a>
			</li>
		<?php endif; ?>
		<?php foreach ($pager->links() as $link): ?>
    <li class="<?= $link['active'] ? 'active' : '' ?>">
        <a href="<?= $link['uri'] ?>">
            <?= esc($link['title']) ?>
        </a>
    </li>
<?php endforeach; ?>
		<?php if ($pager->hasNextPage()): ?>
			<li>
				<a href="<?= $pager->getNextPage() ?>" aria-label="Suivant">Suivant
					&raquo;</a>
			</li>
		<?php endif; ?>
	</ul>
</nav>