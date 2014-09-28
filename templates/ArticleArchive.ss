<section id="article-archive">
	<h2 class="heading"><span>Archive</span></h2>

	<ul class="nested-menu">
		<li class="first <% if not HasActiveArchive %>active<% end_if %> type-all"><a href="$ArticleHolder.Link">Show Everything</a></li>
	<% loop ArticleYearArchive %><% if ArticleMonthArchive %>
		<li class="<% if IsSection %>section<% end_if %> <% if IsActive %>active<% end_if %>">
			<a href="$Link">$Title <span>($ArticleCount)</span></a>
			<ul>
			<% loop ArticleMonthArchive %>
				<li class="<% if IsSection %>section<% end_if %> <% if IsActive %>active<% end_if %>"><a href="$Link">$Title <span>($ArticleCount)</span></a></li>
			<% end_loop %>
			</ul>
		</li>
	<% end_if %><% end_loop %>
	</ul>
</section><!-- #article-archive -->