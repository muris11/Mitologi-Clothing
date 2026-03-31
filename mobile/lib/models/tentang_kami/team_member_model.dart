class TeamMember {
  final int id;
  final String name;
  final String role; // mapped from 'position' in API
  final String? imageUrl; // mapped from 'photo_url' in API
  final int? parentId;
  final int level;
  final int sortOrder;

  TeamMember({
    required this.id,
    required this.name,
    required this.role,
    this.imageUrl,
    this.parentId,
    required this.level,
    required this.sortOrder,
  });

  factory TeamMember.fromJson(Map<String, dynamic> json) {
    return TeamMember(
      id: json['id'] as int,
      name: json['name'] as String,
      role: json['position'] as String? ?? json['role'] as String? ?? '',
      imageUrl: json['photo_url'] as String? ?? json['image_url'] as String?,
      parentId: json['parent_id'] as int?,
      level: json['level'] as int? ?? 0,
      sortOrder: json['sort_order'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'position': role,
      'photo_url': imageUrl,
      'parent_id': parentId,
      'level': level,
      'sort_order': sortOrder,
    };
  }
}
