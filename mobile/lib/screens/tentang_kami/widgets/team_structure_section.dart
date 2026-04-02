import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../config/theme.dart';
import '../../../utils/storage_url.dart';
import '../../../models/tentang_kami/team_member_model.dart';

class TeamStructureSection extends StatelessWidget {
  final List<TeamMember> teamMembers;

  const TeamStructureSection({super.key, required this.teamMembers});

  List<TeamMember> _getRoots() {
    final roots = teamMembers.where((m) => m.parentId == null).toList();
    roots.sort((a, b) => a.level.compareTo(b.level));
    return roots;
  }

  List<TeamMember> _getChildren(int parentId) {
    final children = teamMembers.where((m) => m.parentId == parentId).toList();
    children.sort((a, b) => a.sortOrder.compareTo(b.sortOrder));
    return children;
  }

  Widget _buildMemberCard(TeamMember member, bool isRoot) {
    final hasPhoto = member.imageUrl != null && member.imageUrl!.isNotEmpty;
    final initials = member.name.split(' ').map((w) => w[0]).take(2).join();
    final shouldShowLarge = isRoot && member.level == 0;

    return Column(
      children: [
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(shouldShowLarge ? 32 : 24),
            border: Border.all(
              color: isRoot
                  ? AppTheme.primary.withValues(alpha: 0.2)
                  : AppTheme.slate100,
              width: 1,
            ),
            boxShadow: isRoot
                ? [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.08),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ]
                : [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.03),
                      blurRadius: 8,
                      offset: const Offset(0, 2),
                    ),
                  ],
          ),
          clipBehavior: Clip.antiAlias,
          child: hasPhoto
              ? CachedNetworkImage(
                  imageUrl: StorageUrl.format(member.imageUrl),
                  width: shouldShowLarge ? 140 : 100,
                  height: shouldShowLarge ? 140 : 100,
                  fit: BoxFit.cover,
                  placeholder: (context, url) => Container(
                    width: shouldShowLarge ? 140 : 100,
                    height: shouldShowLarge ? 140 : 100,
                    color: AppTheme.slate100,
                    child: Center(
                      child: SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: AppTheme.primary,
                        ),
                      ),
                    ),
                  ),
                  errorWidget: (context, url, error) => Container(
                    width: shouldShowLarge ? 140 : 100,
                    height: shouldShowLarge ? 140 : 100,
                    color: AppTheme.slate50,
                    child: Center(
                      child: Text(
                        initials,
                        style: TextStyle(
                          fontSize: shouldShowLarge ? 36 : 24,
                          fontWeight: FontWeight.w900,
                          color: AppTheme.primary,
                        ),
                      ),
                    ),
                  ),
                )
              : Container(
                  width: shouldShowLarge ? 140 : 100,
                  height: shouldShowLarge ? 140 : 100,
                  color: AppTheme.slate50,
                  child: Center(
                    child: Text(
                      initials,
                      style: TextStyle(
                        fontSize: shouldShowLarge ? 36 : 24,
                        fontWeight: FontWeight.w900,
                        color: AppTheme.primary,
                      ),
                    ),
                  ),
                ),
        ),
        const SizedBox(height: 12),
        Text(
          member.name,
          textAlign: TextAlign.center,
          style: TextStyle(
            fontSize: shouldShowLarge ? 16 : 13,
            fontWeight: FontWeight.w800,
            color: AppTheme.primary,
            height: 1.2,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          member.role,
          textAlign: TextAlign.center,
          style: TextStyle(
            fontSize: shouldShowLarge ? 11 : 9,
            fontWeight: FontWeight.w600,
            color: isRoot ? AppTheme.accent : AppTheme.slate500,
            letterSpacing: 1.5,
          ),
        ),
      ],
    );
  }

  Widget _buildTreeLevel(List<TeamMember> nodes, bool isRoot) {
    if (nodes.isEmpty) return const SizedBox.shrink();

    return Column(
      children: [
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: nodes.asMap().entries.map((entry) {
              final index = entry.key;
              final node = entry.value;
              final children = _getChildren(node.id);
              final shouldShowLarge = isRoot && node.level == 0;

              return Padding(
                padding: EdgeInsets.only(
                  left: index == 0 ? 0 : (shouldShowLarge ? 24 : 16),
                  right: index == nodes.length - 1
                      ? 0
                      : (shouldShowLarge ? 24 : 16),
                ),
                child: Column(
                  children: [
                    if (!isRoot)
                      Container(
                        width: 1,
                        height: 32,
                        color: AppTheme.accent.withValues(alpha: 0.3),
                      ),
                    if (!isRoot) const SizedBox(height: 16),
                    _buildMemberCard(node, isRoot),
                    if (children.isNotEmpty) ...[
                      const SizedBox(height: 16),
                      Container(
                        width: 1,
                        height: 32,
                        color: AppTheme.accent.withValues(alpha: 0.3),
                      ),
                      if (children.length > 1) ...[
                        const SizedBox(height: 0),
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Container(
                              width: 1,
                              height: 1,
                              color: Colors.transparent,
                            ),
                          ],
                        ),
                      ],
                      _buildTreeLevel(children, false),
                    ],
                  ],
                ),
              );
            }).toList(),
          ),
        ),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    if (teamMembers.isEmpty) return const SizedBox.shrink();

    final roots = _getRoots();

    return Container(
      color: const Color(0xFFF8FAFC),
      padding: const EdgeInsets.symmetric(vertical: 48),
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            decoration: BoxDecoration(
              color: AppTheme.primary.withValues(alpha: 0.05),
              borderRadius: BorderRadius.circular(20),
            ),
            child: const Text(
              'TIM KAMI',
              style: TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w800,
                color: AppTheme.primary,
                letterSpacing: 0.5,
              ),
            ),
          ),
          const SizedBox(height: 20),
          const Text(
            'Struktur Organisasi',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.w900,
              color: AppTheme.primary,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            'Kenali tim profesional di balik setiap produk\nberkualitas dari perusahaan kami.',
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 14,
              height: 1.6,
              color: AppTheme.slate600,
            ),
          ),
          const SizedBox(height: 48),
          _buildTreeLevel(roots, true),
        ],
      ),
    );
  }
}
