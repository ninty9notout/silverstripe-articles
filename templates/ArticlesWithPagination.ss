<section class="article-listing" itemscope itemtype="http://schema.org/ItemList">
	<div class="columns horizontal-gutters">
	<% loop Articles %>
		<% include ArticlePartial %>
	<% end_loop %>
	</div>
</section><!-- .article-listing -->

<% if Pagination %>
	<section id="pagination">
		$Pagination
	</section><!-- #pagination -->
<% end_if %>