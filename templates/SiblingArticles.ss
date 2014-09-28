<section id="sibling-articles">
	<div class="columns half-horizontal-gutters">
		<div class="all-50 quarter-padding">
		<% if PreviousArticle %>
			<p>Previous Article</p>
			<h2><a href="$PreviousArticle.Link">$PreviousArticle.Title.XML</a></h2>
		<% end_if %>
		</div>

		<div class="all-50 quarter-padding">
		<% if NextArticle %>
			<p>Next Article</p>
			<h2><a href="$NextArticle.Link">$NextArticle.Title.XML</a></h2>
		<% end_if %>
		</div>
	</div>
</section><!-- #sibling-articles -->