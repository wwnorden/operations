<section class="wrapper">
    <div class="inner">
        <%-- Breadcrumbs --%>
        <% include BreadCrumbs %>
        <hr>

        <h1>$Headline.RAW $Year</h1>
        <br>
        <% if $Lead %><p>$Lead.RAW</p><% end_if %>
        <% if $Content %>
            $Content
        <% end_if %>

        <% if $PaginatedOperations %>
            <% loop $PaginatedOperations %>
                <h3 id="operation-$ID">$Title.RAW</h3>
                <p><strong><% _t('WWN\Operations\OperationArticle.db_Number','Number') %></strong> $Number |
                    <strong><% _t('WWN\Operations\OperationArticle.db_Date','Date') %></strong> $Date.Format('dd.MM.y') |
                    <strong><% _t('WWN\Operations\OperationArticle.db_Begin','Begin') %></strong> $Begin.Format('HH:mm') <% _t('WWN\Operations\OperationArticle.clock','oclock') %> |
                    <strong><% _t('WWN\Operations\OperationArticle.db_End','End') %></strong> $End.Format('HH:mm') <% _t('WWN\Operations\OperationArticle.clock','oclock') %>
                    <% if $People %>
                        | <strong><i class="fas" title="$People <% _t('WWN\Operations\OperationArticle.db_People','People') %>">&#xf0c0;</i>&nbsp; $People</strong>
                    <% end_if %>
                </p>
                <p>$Content</p>
                <% if $OperationForces %>
                    <p><strong><% _t('WWN\Operations\OperationArticle.many_many_OperationForces','Operation forces') %></strong></p>
                    <ul class="actions">
                        <% loop $OperationForces %>
                            <li class="margin-bottom">
                            <% if $URL %>
                                <a href="$URL" title="$Title" target="_blank" class="button alt small">$Title</a>
                            <% else %>
                                $Title
                            <% end_if %>
                            </li>
                        <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $Links %>
                    <p><strong><% _t('WWN\Operations\OperationArticle.has_many_Links','Links') %></strong></p>
                    <ul class="actions">
                        <% loop $Links %>
                            <li class="margin-bottom">
                                <a href="$URL" title="$Source" target="_blank" class="button alt small">$Title</a>
                            </li>
                        <% end_loop %>
                    </ul>
                <% end_if %>
                <% if $OperationImages %>
                    <p><strong><% _t('WWN\Operations\OperationArticle.has_many_OperationImages','Images') %></strong></p>
                    <div id="$ID">
                        <% loop $OperationImages %>
                            <a href="$Image.URL" alt="$Title" title="$Title">
                                <img src="$Image.URL"
                                     class="img-rounded image-list"
                                     alt="$Title"
                                     title="$Title">
                            </a>
                        <% end_loop %>
                    </div>
                <% end_if %>
                <br>
                <% if not $last %>
                    <hr>
                <% end_if %>
            <% end_loop %>

            <hr>

            <% if $PaginatedOperations.MoreThanOnePage %>
                <% if $PaginatedOperations.NotFirstPage %>
                    <a class="prev button alt small" href="$PaginatedOperations.PrevLink"><% _t('WWN\Operations\OperationArticle.prev','Previous') %></a>
                <% end_if %>
                <% loop $PaginatedOperations.PaginationSummary %>
                    <% if $CurrentBool %>
                        <p class="button alt disabled">$PageNum</p>
                    <% else %>
                        <% if $Link %>
                            <a href="$Link" class="button alt small">$PageNum</a>
                        <% else %>
                            ...
                        <% end_if %>
                    <% end_if %>
                <% end_loop %>
                <% if $PaginatedOperations.NotLastPage %>
                    <a class="next button alt small" href="$PaginatedOperations.NextLink"><% _t('WWN\Operations\OperationArticle.next','Next') %></a>
                <% end_if %>
            <% end_if %>
        <% end_if %>
    </div>
</section>
