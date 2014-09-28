<section id="article-types">
	<h2 class="heading"><span>Filter by Type</span></h2>

	<ul class="nested-menu">
		<li class="first <% if not HasActiveType %>active<% end_if %> type-all"><a href="<% if IsArticle %>$Parent.Link<% else %>$Link<% end_if %>">Show Everything</a></li>
	<% loop ArticleTypes %>
		<li class="type-$URLSegment <% if Active %>active<% end_if %>"><a href="$Link">$Plural</a></li>
	<% end_loop %>
	</ul>
</section><!-- #article-types -->